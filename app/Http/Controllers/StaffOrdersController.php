<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Staff "Sales Records" (orders) screen.
 *
 * This is the same page the admin sees. Every order action delegates to
 * AdminOrdersController so the staff and admin screens always share one set
 * of business rules (status transitions, 5-hour pickup expiry, stock release,
 * refund proof handling) and can never drift apart. Only the route/guard
 * (auth:staff) and the view (staffs.orders) differ.
 *
 * This follows the same pattern StaffController uses for the dashboard charts.
 */
class StaffOrdersController extends Controller
{
    // ── Page ─────────────────────────────────────────────────────────────────

    /**
     * Sales Records page. The table itself is filled by data() via fetch(),
     * so the view only needs the site branding.
     */
    public function index(): View
    {
        return view('staffs.orders', [
            'logo2'    => Setting::get('logo2'),
            'siteName' => Setting::get('site_name', 'Pharmacy'),
        ]);
    }

    // ── Data feed (JSON) ─────────────────────────────────────────────────────

    /** JSON feed for the table; also sweeps expired "For Pick Up" orders. */
    public function data(): JsonResponse
    {
        return $this->admin()->data();
    }

    // ── Status transitions ───────────────────────────────────────────────────

    /** Pending → Confirmed ("Approve" button). */
    public function confirm(Order $order): JsonResponse
    {
        return $this->admin()->confirm($order);
    }

    /** Confirmed → Ready / For Pick Up ("Reserve" button). Starts the 5-hour clock. */
    public function ready(Order $order): JsonResponse
    {
        return $this->admin()->ready($order);
    }

    /** Ready → Picked up / Completed (receipt "Submit"). */
    public function complete(Order $order): JsonResponse
    {
        return $this->admin()->complete($order);
    }

    /** Cancel from any non-terminal state; restocks the reserved quantity. */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        return $this->admin()->cancel($request, $order);
    }

    /** Record a refund (proof-of-refund image) for a cancelled, paid-online order. */
    public function refund(Request $request, Order $order): JsonResponse
    {
        return $this->admin()->refund($request, $order);
    }

    /** Soft-delete a finished (completed or cancelled) order. */
    public function destroy(Order $order): JsonResponse
    {
        return $this->admin()->destroy($order);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /** Resolved through the container so OrderService is injected. */
    private function admin(): AdminOrdersController
    {
        return app(AdminOrdersController::class);
    }
}