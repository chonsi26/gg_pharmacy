<?php

namespace App\Http\Middleware;

use App\Models\Log;
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
 * made inside the admin portal, plus admin login / logout / registration.
 *
 * Applied once to the whole `admin` route group in routes/web.php, so any
 * route you add there later is logged automatically — no per-controller code.
 *
 * Failed requests (validation errors, 4xx/5xx, "cannot be cancelled", bad
 * credentials …) are NOT logged, and logging can never break a request: any
 * error while writing the log is reported and swallowed.
 */
class LogAdminActivity
{
    /** State-changing routes that are just noise (opening notifications). */
    private const SKIPPED_ROUTES = [
        'admin.notifications.read',
        'admin.notifications.readAll',
    ];

    /** Routes whose module/action/wording don't follow the generic pattern. */
    private const SPECIAL = [
        'admin.login'                    => ['Authentication', Log::ACTION_LOGIN,      'logged in'],
        'admin.logout'                   => ['Authentication', Log::ACTION_LOGOUT,     'logged out'],
        'admin.register'                 => ['Authentication', Log::ACTION_REGISTERED, 'registered a new admin account'],
        'admin.profile.update'           => ['Profile',        Log::ACTION_UPDATED,    'updated their profile details'],
        'admin.profile.photo'            => ['Profile',        Log::ACTION_UPDATED,    'updated their profile photo'],
        'admin.profile.password'         => ['Profile',        Log::ACTION_UPDATED,    'changed their password'],
        'admin.pharmacy.settings.update' => ['Pharmacy',       Log::ACTION_UPDATED,    'updated the general pharmacy settings'],
    ];

    /**
     * Route-name prefix => [module, noun]. Order matters: the more specific
     * prefix (inventory.categories.) must come before the general one (inventory.).
     */
    private const RESOURCES = [
        'admin.inventory.categories.'          => ['Categories',       'category'],
        'admin.inventory.brands.'              => ['Brands',           'brand'],
        'admin.inventory.'                     => ['Inventory',        'product'],
        'admin.orders.'                        => ['Orders',           'order'],
        'admin.staffs.'                        => ['Staffs',           'staff account'],
        'admin.stocks.'                        => ['Stocks',           'stock batch'],
        'admin.financials.'                    => ['Financials',       'financial record'],
        'admin.pharmacy_sliders.'              => ['Pharmacy',         'slider'],
        'admin.pharmacy_promo_banners.'        => ['Pharmacy',         'promo banner'],
        'admin.pharmacy_full_width_banners.'   => ['Pharmacy',         'full-width banner'],
        'admin.pharmacy_sections.'             => ['Pharmacy',         'section'],
        'admin.pharmacy_categories.'           => ['Pharmacy',         'storefront category'],
        'admin.pharmacy_brands.'               => ['Pharmacy',         'storefront brand'],
        'admin.pharmacy_payment_accounts.'     => ['Pharmacy',         'payment account'],
    ];

    /** Route suffix => [action key, wording that precedes "<noun> <label>"]. */
    private const VERBS = [
        'store'    => [Log::ACTION_CREATED,   'added'],
        'update'   => [Log::ACTION_UPDATED,   'updated'],
        'destroy'  => [Log::ACTION_DELETED,   'deleted'],
        'confirm'  => [Log::ACTION_APPROVED,  'approved'],
        'ready'    => [Log::ACTION_READY,     'marked as ready for pick up'],
        'complete' => [Log::ACTION_COMPLETED, 'completed'],
        'cancel'   => [Log::ACTION_CANCELLED, 'cancelled'],
        'refund'   => [Log::ACTION_REFUNDED,  'recorded a refund for'],
        'activate' => [Log::ACTION_ACTIVATED, 'activated'],
        'badge'    => [Log::ACTION_UPDATED,   'updated the badge of'],
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
        $adminBefore = Auth::guard('admin')->user();

        $response = $next($request);

        try {
            $admin = $routeName === 'admin.logout'
                ? $adminBefore
                : (Auth::guard('admin')->user() ?? $adminBefore);

            if ($admin && $this->succeeded($request, $response)) {
                $this->record($request, $response, $routeName, $admin);
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

    private function record(Request $request, Response $response, string $routeName, $admin): void
    {
        [$module, $action, $phrase, $noun] = $this->resolveRoute($routeName);

        $model   = $this->boundModel($request);
        $message = $this->resultMessage($request, $response);
        $label   = $this->subjectLabel($model, $message, $request);

        $description = $phrase;
        if ($noun !== null) {
            $description .= ' ' . $noun . ($label !== null ? ' "' . $label . '"' : '');
        }
        if ($routeName === 'admin.orders.cancel' && $request->filled('reason')) {
            $description .= ' — ' . Str::limit((string) $request->input('reason'), 120);
        }

        $isAuth = $module === 'Authentication';

        Log::record([
            'admin_id'      => $admin->getKey(),
            'admin_name'    => $this->adminName($admin),
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
                [$action, $phrase] = self::VERBS[$suffix] ?? [Log::ACTION_OTHER, "performed \"{$suffix}\" on"];

                return [$module, $action, $phrase, $noun];
            }
        }

        // A new admin route nobody mapped yet: still logged, just generically.
        return ['System', Log::ACTION_OTHER, 'performed ' . Str::after($routeName, 'admin.'), null];
    }

    /** First route-model-bound parameter (Product, Order, Stock, Staff …), if any. */
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

            foreach (['order_number', 'name', 'title', 'account_name', 'bank_name', 'description'] as $attribute) {
                $value = $model->getAttribute($attribute);
                if (is_string($value) && trim($value) !== '') {
                    return Str::limit(trim($value), 80);
                }
            }

            $full = trim(($model->getAttribute('first_name') ?? '') . ' ' . ($model->getAttribute('last_name') ?? ''));
            if ($full !== '') {
                return $full;
            }

            return '#' . $model->getKey();
        }

        // "Create" routes have no bound model: pull the name out of the
        // controller's message, e.g.  Staff "Jane Cruz" added successfully.
        if ($message && preg_match('/["“]([^"”]{1,80})["”]/u', $message, $m)) {
            return $m[1];
        }

        foreach (['name', 'title', 'description'] as $field) {
            $value = $request->input($field);
            if (is_string($value) && trim($value) !== '') {
                return Str::limit(trim($value), 80);
            }
        }

        $full = trim((string) $request->input('first_name') . ' ' . (string) $request->input('last_name'));

        return $full !== '' ? $full : null;
    }

    private function adminName($admin): string
    {
        $name = trim(($admin->first_name ?? '') . ' ' . ($admin->last_name ?? ''));

        return $name !== '' ? $name : (string) ($admin->username ?? $admin->email ?? 'Admin');
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
