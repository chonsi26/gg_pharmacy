<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\StaffLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Staff portal → Settings page.
 *
 * Mirrors the admin Settings screen (AdminController@settings), trimmed to
 * what staff can use:
 *   • Appearance (light / dark)   – pure front-end, saved in localStorage
 *   • Activity Log                – JSON feed from logs() below, filled by the
 *                                   LogStaffActivity middleware (staff_logs table)
 *   • Notifications               – full list modal, backed by
 *                                   StaffNotificationsController (all / read / readAll)
 *   • Help & Terms of Service     – static modals
 *   • Logout                      – POSTs to staff.logout
 */
class StaffSettingsController extends Controller
{
    /** Default number of entries returned per page. */
    private const PER_PAGE = 20;

    /**
     * false → every staff member sees the whole staff feed (same as the admin
     *         Activity Log, which shows all admins).
     * true  → each staff member only sees their own activity.
     */
    private const ONLY_OWN_LOGS = false;

    /**
     * Pill colour (matches the .pill classes in staffs/settings.blade.php) per action.
     */
    private const TONES = [
        StaffLog::ACTION_CREATED    => 'ok',
        StaffLog::ACTION_UPDATED    => 'blue',
        StaffLog::ACTION_DELETED    => 'danger',
        StaffLog::ACTION_APPROVED   => 'ok',
        StaffLog::ACTION_READY      => 'blue',
        StaffLog::ACTION_COMPLETED  => 'ok',
        StaffLog::ACTION_CANCELLED  => 'danger',
        StaffLog::ACTION_REFUNDED   => 'warning',
        StaffLog::ACTION_ACTIVATED  => 'ok',
        StaffLog::ACTION_LOGIN      => 'blue',
        StaffLog::ACTION_LOGOUT     => 'blue',
        StaffLog::ACTION_REGISTERED => 'ok',
        StaffLog::ACTION_OTHER      => 'blue',
    ];

    /**
     * GET /staff/settings
     */
    public function index(): View
    {
        $logo2    = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');

        // The layout's sidebar chip reads $staff (same variable the dashboard view uses).
        $staff = Auth::guard('staff')->user();

        return view('staffs.settings', compact('logo2', 'siteName', 'staff'));
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Activity Log (Settings → Activity Log modal)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * GET /staff/settings/logs — JSON feed for the Activity Log modal, newest first.
     *
     * Supports the same filters as the modal's toolbar:
     *   ?search=<term>        (staff name, description, module, or subject)
     *   ?module=<name>|all
     *   ?action=<key>|all
     *   ?date_from=YYYY-MM-DD
     *   ?date_to=YYYY-MM-DD
     *   ?page=N&per_page=N    ("Load more" asks for the next page)
     *
     * The filter dropdown options (modules/actions) are returned with the
     * first page only.
     */
    public function logs(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'search'    => ['nullable', 'string', 'max:100'],
            'module'    => ['nullable', 'string', 'max:50'],
            'action'    => ['nullable', 'string', 'max:30'],
            'date_from' => ['nullable', 'date'],
            'date_to'   => ['nullable', 'date', 'after_or_equal:date_from'],
            'page'      => ['nullable', 'integer', 'min:1'],
            'per_page'  => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $paginator = $this->baseQuery()
            ->search($validated['search'] ?? null)
            ->ofModule($validated['module'] ?? null)
            ->ofAction($validated['action'] ?? null)
            ->createdBetween($validated['date_from'] ?? null, $validated['date_to'] ?? null)
            ->latestFirst()
            ->paginate($validated['per_page'] ?? self::PER_PAGE);

        $firstPage = $paginator->currentPage() === 1;

        return response()->json([
            'logs'      => $paginator->getCollection()
                ->map(fn (StaffLog $log) => $this->formatLog($log))
                ->values(),
            'total'     => $paginator->total(),
            'next_page' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
            'modules'   => $firstPage ? $this->distinctValues('module') : null,
            'actions'   => $firstPage ? $this->distinctValues('action') : null,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────────────────

    /** Every log query starts here so the ONLY_OWN_LOGS rule applies everywhere. */
    private function baseQuery(): Builder
    {
        return StaffLog::query()->when(
            self::ONLY_OWN_LOGS,
            fn (Builder $q) => $q->where('staff_id', Auth::guard('staff')->id())
        );
    }

    /** Shapes a StaffLog row into the object the staffs/settings.blade.php JS expects. */
    private function formatLog(StaffLog $log): array
    {
        return [
            'id'          => $log->id,
            'staff'       => $log->staff_name ?: 'System',
            'description' => $log->description,
            'module'      => $log->module,
            'action'      => $log->action,
            'tone'        => self::TONES[$log->action] ?? 'blue',
            'subject'     => $log->subject_label,
            'ip'          => $log->ip_address,
            'time'        => $log->timeLabel(),
            'full_time'   => $log->created_at->format('M j, Y · g:i:s A'),
        ];
    }

    /** Distinct values of a staff_logs column, for the filter dropdowns. */
    private function distinctValues(string $column)
    {
        return $this->baseQuery()
            ->select($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }
}