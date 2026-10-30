<?php

namespace App\Http\Middleware;

use App\Models\StaffLog;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Records every successful state-changing action (POST / PUT / PATCH / DELETE)
 * made inside the staff portal, plus staff login / logout / registration.
 *
 * Applied once to the whole `staff` route group in routes/web.php, so any
 * route you add there later is logged automatically — no per-controller code.
 *
 * Failed requests (validation errors, 4xx/5xx, bad credentials …) are NOT
 * logged, and logging can never break a request: any error while writing the
 * log is reported and swallowed.
 */
class LogStaffActivity
{
    /** State-changing routes that are just noise (opening notifications). */
    private const SKIPPED_ROUTES = [
        'staff.notifications.read',
        'staff.notifications.readAll',
    ];

    /** Routes whose module/action/wording don't follow the generic pattern. */
    private const SPECIAL = [
        'staff.login.submit'    => ['Authentication', StaffLog::ACTION_LOGIN,      'logged in'],
        'staff.logout'          => ['Authentication', StaffLog::ACTION_LOGOUT,     'logged out'],
        'staff.register.submit' => ['Authentication', StaffLog::ACTION_REGISTERED, 'registered a new staff account'],
        'staff.profile.update'  => ['Profile',        StaffLog::ACTION_UPDATED,    'updated their profile details'],
        'staff.profile.photo'   => ['Profile',        StaffLog::ACTION_UPDATED,    'updated their profile photo'],
        'staff.profile.password'=> ['Profile',        StaffLog::ACTION_UPDATED,    'changed their password'],
    ];

    /**
     * Route-name prefix => [module, noun]. Order matters: the more specific
     * prefix (inventory.categories.) must come before the general one (inventory.).
     */
    private const RESOURCES = [
        'staff.inventory.categories.' => ['Categories', 'category'],
        'staff.inventory.brands.'     => ['Brands',     'brand'],
        'staff.inventory.'            => ['Inventory',  'product'],
        'staff.orders.'               => ['Orders',     'order'],
        'staff.stocks.'               => ['Stocks',     'stock batch'],
    ];

    /** Route suffix => [action key, wording that precedes "<noun> <label>"]. */
    private const VERBS = [
        'store'    => [StaffLog::ACTION_CREATED,   'added'],
        'update'   => [StaffLog::ACTION_UPDATED,   'updated'],
        'destroy'  => [StaffLog::ACTION_DELETED,   'deleted'],
        'confirm'  => [StaffLog::ACTION_APPROVED,  'approved'],
        'ready'    => [StaffLog::ACTION_READY,     'marked as ready for pick up'],
        'complete' => [StaffLog::ACTION_COMPLETED, 'completed'],
        'cancel'   => [StaffLog::ACTION_CANCELLED, 'cancelled'],
        'refund'   => [StaffLog::ACTION_REFUNDED,  'recorded a refund for'],
        'activate' => [StaffLog::ACTION_ACTIVATED, 'activated'],
        'badge'    => [StaffLog::ACTION_UPDATED,   'updated the badge of'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        // Only actions that change something are logged — never page views.
        if (
            in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)
            || ! $routeName
            || in_array($routeName, self::SKIPPED_ROUTES, true)
        ) {
            return $next($request);
        }

        // Captured up front: by the time /logout returns, the session is gone.
        $staffBefore = Auth::guard('staff')->user();

        $response = $next($request);

        try {
            $staff = $routeName === 'staff.logout'
                ? $staffBefore
                : (Auth::guard('staff')->user() ?? $staffBefore);

            if ($staff && $this->succeeded($request, $response)) {
                $this->record($request, $response, $routeName, $staff);
            }
        } catch (Throwable $e) {
            report($e); // never let logging break the actual request
        }

        return $response;
    }

    // ─────────────────────────────────────────────────────────────────────

    /** 2xx/3xx, and not a "redirect back with errors" (failed validation / login). */
    private function succeeded(Request $request, Response $response): bool
    {
        if ($response->getStatusCode() >= 400) {
            return false;
        }

        if ($response->isRedirection() && $request->hasSession()) {
            $session = $request->session();

            if ($session->has('errors') || $session->has('error')) {
                return false;
            }
        }

        return true;
    }

    private function record(Request $request, Response $response, string $routeName, $staff): void
    {
        [$module, $action, $phrase, $noun] = $this->resolveRoute($routeName);

        $model   = $this->boundModel($request);
        $message = $this->resultMessage($request, $response);
        $label   = $this->subjectLabel($model, $message, $request);

        $description = $phrase;
        if ($noun !== null) {
            $description .= ' ' . $noun . ($label !== null ? ' "' . $label . '"' : '');
        }
        if ($routeName === 'staff.orders.cancel' && $request->filled('reason')) {
            $description .= ' — ' . Str::limit((string) $request->input('reason'), 120);
        }

        $isAuth = $module === 'Authentication';

        StaffLog::record([
            'staff_id'      => $staff->getKey(),
            'staff_name'    => $this->staffName($staff),
            'module'        => $module,
            'action'        => $action,
            'description'   => $description,
            'subject_type'  => $model ? get_class($model) : null,
            'subject_id'    => $model?->getKey(),
            'subject_label' => $label,
            'route_name'    => $routeName,
            'method'        => $request->method(),
            'url'           => Str::limit('/' . ltrim($request->path(), '/'), 500, ''),
            'ip_address'    => $request->ip(),
            'user_agent'    => Str::limit((string) $request->userAgent(), 500, ''),
            // Never keep what was typed on the auth screens.
            'properties'    => $isAuth ? null : array_filter([
                'request' => $this->sanitize($request->except(['_token', '_method'])),
                'result'  => $message,
            ], fn ($v) => $v !== null && $v !== []),
        ]);
    }

    /** @return array{0:string,1:string,2:string,3:?string} module, action, verb phrase, noun */
    private function resolveRoute(string $routeName): array
    {
        if (isset(self::SPECIAL[$routeName])) {
            [$module, $action, $phrase] = self::SPECIAL[$routeName];

            return [$module, $action, $phrase, null];
        }

        $suffix = Str::afterLast($routeName, '.');

        foreach (self::RESOURCES as $prefix => [$module, $noun]) {
            if (str_starts_with($routeName, $prefix)) {
                [$action, $phrase] = self::VERBS[$suffix] ?? [StaffLog::ACTION_OTHER, "performed \"{$suffix}\" on"];

                return [$module, $action, $phrase, $noun];
            }
        }

        // A new staff route nobody mapped yet: still logged, just generically.
        return ['System', StaffLog::ACTION_OTHER, 'performed ' . Str::after($routeName, 'staff.'), null];
    }

    /** First route-model-bound parameter (Product, Order, Stock …), if any. */
    private function boundModel(Request $request): ?Model
    {
        foreach ($request->route()?->parameters() ?? [] as $parameter) {
            if ($parameter instanceof Model) {
                return $parameter;
            }
        }

        return null;
    }

    /** The controller's own success message (JSON `message` or flashed `status`). */
    private function resultMessage(Request $request, Response $response): ?string
    {
        $message = null;

        if ($response instanceof JsonResponse) {
            $data    = $response->getData(true);
            $message = is_array($data) ? ($data['message'] ?? null) : null;
        } elseif ($request->hasSession()) {
            $message = $request->session()->get('status');
        }

        return is_string($message) && $message !== '' ? Str::limit($message, 255, '') : null;
    }

    /** Best human label for what was acted on: "ORD-1001", "Paracetamol 500mg", … */
    private function subjectLabel(?Model $model, ?string $message, Request $request): ?string
    {
        if ($model) {
            if (method_exists($model, 'stockNo')) {
                try {
                    return (string) $model->stockNo();
                } catch (Throwable) {
                    // fall through to the generic attributes
                }
            }

            foreach (['order_number', 'name', 'title', 'description'] as $attribute) {
                $value = $model->getAttribute($attribute);
                if (is_string($value) && trim($value) !== '') {
                    return Str::limit(trim($value), 80);
                }
            }

            return '#' . $model->getKey();
        }

        // "Create" routes have no bound model: pull the name out of the
        // controller's message, e.g.  Product "Paracetamol" added successfully.
        if ($message && preg_match('/["“]([^"”]{1,80})["”]/u', $message, $m)) {
            return $m[1];
        }

        foreach (['name', 'title', 'description'] as $field) {
            $value = $request->input($field);
            if (is_string($value) && trim($value) !== '') {
                return Str::limit(trim($value), 80);
            }
        }

        return null;
    }

    private function staffName($staff): string
    {
        $name = trim(($staff->first_name ?? '') . ' ' . ($staff->last_name ?? ''));

        return $name !== '' ? $name : (string) ($staff->name ?? $staff->username ?? $staff->email ?? 'Staff');
    }

    /** Drop secrets and files, truncate long strings, cap nesting. */
    private function sanitize(array $input, int $depth = 0): array
    {
        $clean = [];

        foreach ($input as $key => $value) {
            if (is_string($key) && preg_match('/password|token|secret|otp/i', $key)) {
                $clean[$key] = '[hidden]';
            } elseif ($value instanceof UploadedFile) {
                $clean[$key] = '[file] ' . $value->getClientOriginalName();
            } elseif (is_array($value)) {
                $clean[$key] = $depth >= 3 ? '[…]' : $this->sanitize($value, $depth + 1);
            } elseif (is_string($value)) {
                $clean[$key] = Str::limit($value, 200);
            } else {
                $clean[$key] = $value;
            }
        }

        return $clean;
    }
}
