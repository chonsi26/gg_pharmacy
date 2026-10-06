<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Expiry Tracking for the staff portal.
 *
 * Same screen and same numbers as the admin expiry page
 * (AdminController@expiry) — only the route/guard (auth:staff) and the
 * sidebar differ. Read-only: the page just monitors batches nearing expiry.
 */
class StaffExpiryController extends Controller
{
    // ── Expiry Tracking ───────────────────────────────────────────────────────

    public function index(): View
    {
        $logo2    = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');

        // Every batch that has an expiry date, soonest-to-expire first, so the
        // most urgent batches are always what staff see at the top.
        $stocks = Stock::with('product:id,name,image')
            ->whereNotNull('expiry_date')
            ->orderBy('expiry_date', 'asc')
            ->get()
            ->map(fn (Stock $stock) => $this->formatExpiryStock($stock))
            ->values();

        return view('staffs.expiry', compact('logo2', 'siteName', 'stocks'));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Shape a Stock model into the flat array the expiry.blade.php grid
     * expects. Buckets each batch into a level (danger/warning/ok) based on
     * days remaining until expiry, and includes the is_active flag with a
     * human-readable label so the frontend can show a status badge without
     * re-deriving it from a boolean.
     */
    private function formatExpiryStock(Stock $stock): array
    {
        $today  = now()->startOfDay();
        $expiry = $stock->expiry_date->copy()->startOfDay();

        // Positive = days remaining, negative = days already expired.
        $daysLeft = (int) $today->diffInDays($expiry, false);

        if ($daysLeft < 0) {
            $level = 'danger';
            $pill  = 'Expired';
        } elseif ($daysLeft <= 30) {
            $level = 'danger';
            $pill  = $daysLeft . ' Days';
        } elseif ($daysLeft <= 90) {
            $level = 'warning';
            $pill  = round($daysLeft / 30) . ' Months';
        } else {
            $level = 'ok';
            $pill  = '6+ Months';
        }

        return [
            'id'           => $stock->id,
            'name'         => $stock->product->name ?? 'Unknown Product',
            'image'        => $this->productImageUrl($stock->product->image ?? null),
            'batch'        => $stock->stockNo(),
            'qty'          => $stock->quantity,
            'expiry'       => $stock->expiry_date->format('M j, Y'),
            'days_left'    => $daysLeft,
            'level'        => $level,
            'pill'         => $pill,
            'is_active'    => $stock->is_active,
            'status'       => $stock->status,                 // Active | Inactive | Out of Stock | Expired
            'status_label' => $stock->is_active ? 'Active' : 'Inactive',
            'max_days'     => 365,
        ];
    }

    /**
     * Resolve a product's `image` column into a browser-usable URL.
     * - Full remote URLs (seeded/imported) are returned as-is.
     * - Anything already prefixed with "storage/" is passed through asset().
     * - Otherwise it's treated as a bare filename living in the products
     *   upload folder (public/storage/products/) and built up from there.
     */
    private function productImageUrl(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        if (Str::startsWith($image, ['http://', 'https://'])) {
            return $image;
        }

        if (Str::startsWith($image, 'storage/')) {
            return asset($image);
        }

        return asset('storage/' . ltrim($image, '/'));
    }
}