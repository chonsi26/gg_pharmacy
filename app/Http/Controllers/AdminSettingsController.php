<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    /** Default / maximum number of entries returned per page. */
    private const PER_PAGE = 20;

    /**
     * Pill colour (matches the .pill classes in settings.blade.php) per action.
     */
    private const TONES = [
        Log::ACTION_CREATED    => 'ok',
        Log::ACTION_UPDATED    => 'blue',
        Log::ACTION_DELETED    => 'danger',
        Log::ACTION_APPROVED   => 'ok',
        Log::ACTION_READY      => 'blue',
        Log::ACTION_COMPLETED  => 'ok',
        Log::ACTION_CANCELLED  => 'danger',
        Log::ACTION_REFUNDED   => 'warning',
        Log::ACTION_ACTIVATED  => 'ok',
        Log::ACTION_LOGIN      => 'blue',
        Log::ACTION_LOGOUT     => 'blue',
        Log::ACTION_REGISTERED => 'ok',
        Log::ACTION_OTHER      => 'blue',
    ];

    // ─────────────────────────────────────────────────────────────────────
    //  Activity Log (Settings → Activity Log modal)
    // ─────────────────────────────────────────────────────────────────────

    /**
     * READ — JSON feed for the Activity Log modal, newest first.
     *
     * Supports the same filters as the modal's toolbar:
     *   ?search=<term>        (admin name, description, module, or subject)
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

        $paginator = Log::query()
            ->search($validated['search'] ?? null)
            ->ofModule($validated['module'] ?? null)
            ->ofAction($validated['action'] ?? null)
            ->createdBetween($validated['date_from'] ?? null, $validated['date_to'] ?? null)
            ->latestFirst()
            ->paginate($validated['per_page'] ?? self::PER_PAGE);

        $firstPage = $paginator->currentPage() === 1;

        return response()->json([
            'logs'      => $paginator->getCollection()
                ->map(fn (Log $log) => $this->formatLog($log))
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

    /** Shapes a Log row into the object the settings.blade.php JS expects. */
    private function formatLog(Log $log): array
    {
        return [
            'id'          => $log->id,
            'admin'       => $log->admin_name ?: 'System',
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

    /** Distinct values of a logs column, for the filter dropdowns. */
    private function distinctValues(string $column)
    {
        return Log::query()
            ->select($column)
            ->distinct()
            ->orderBy($column)
            ->pluck($column);
    }
}
