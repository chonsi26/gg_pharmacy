<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Staff-side stocks screen. Mirrors AdminStocksController (same rules, same
 * JSON shapes) so the shared stocks blade works unchanged — only the guard
 * (auth:staff), the view, and the redirect targets (staff.stocks) differ.
 */
class StaffStocksController extends Controller
{
    // ── Page ─────────────────────────────────────────────────────────────────

    public function index(): View
    {
        $logo2    = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');
        $staff    = Auth::guard('staff')->user();

        // Products for the Add/Edit Stock modal's medicine dropdown.
        $products = Product::active()->orderBy('name')->get(['id', 'name']);

        // All stock batches for the table, newest first.
        $stocks = $this->allStocksFormatted();

        return view('staffs.stocks', compact('logo2', 'siteName', 'staff', 'products', 'stocks'));
    }

    // ── Add ──────────────────────────────────────────────────────────────────

    /**
     * Create a new stock batch for a product. If the batch is being added
     * as "Active", the product must not already have another ongoing
     * active batch (is_active, quantity > 0, not expired).
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validatedStockData($request);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($data['is_active'] && $this->hasOngoingActiveStock((int) $data['product_id'])) {
            return $this->conflictResponse($request);
        }

        $stock = Stock::create($data);
        $stock->load('product');

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => "Stock \"{$stock->stockNo()}\" for \"{$stock->product->name}\" added successfully.",
                'stock'    => $this->formatStock($stock),
                'stocks'   => $this->allStocksFormatted(),
                'products' => $this->allProductsFormatted(),
            ], 201);
        }

        return redirect()->route('staff.stocks')->with('status', 'Stock added successfully.');
    }

    // ── Update ───────────────────────────────────────────────────────────────

    /**
     * Update an existing stock batch. If the update would switch this
     * batch to "Active" while another ongoing active batch already exists
     * for the same product, the update is rejected.
     */
    public function update(Request $request, Stock $stock): JsonResponse|RedirectResponse
    {
        $data = $this->validatedStockData($request);
        $data['is_active'] = $request->boolean('is_active', $stock->is_active);

        if ($data['is_active'] && $this->hasOngoingActiveStock((int) $data['product_id'], $stock->id)) {
            return $this->conflictResponse($request);
        }

        $stock->update($data);
        $stock->load('product');

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => "Stock \"{$stock->stockNo()}\" updated successfully.",
                'stock'    => $this->formatStock($stock),
                'stocks'   => $this->allStocksFormatted(),
                'products' => $this->allProductsFormatted(),
            ]);
        }

        return redirect()->route('staff.stocks')->with('status', 'Stock updated successfully.');
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function destroy(Request $request, Stock $stock): JsonResponse|RedirectResponse
    {
        $label = $stock->stockNo();

        $stock->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => "Stock \"{$label}\" deleted successfully.",
                'stocks'   => $this->allStocksFormatted(),
                'products' => $this->allProductsFormatted(),
            ]);
        }

        return redirect()->route('staff.stocks')->with('status', "Stock \"{$label}\" deleted successfully.");
    }

    // ── Quick-activate (from the Products modal) ────────────────────────────

    /**
     * Immediately make $stock the active batch for its product. Any other
     * active batch for the same product is deactivated first so a product
     * never ends up with two ongoing active batches.
     */
    public function activate(Request $request, Stock $stock): JsonResponse|RedirectResponse
    {
        if ($stock->quantity <= 0) {
            $message = 'This batch has no remaining quantity and cannot be activated.';
        } elseif ($stock->expiry_date && $stock->expiry_date->lt(now())) {
            $message = 'This batch has already expired and cannot be activated.';
        } else {
            $message = null;
        }

        if ($message) {
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        DB::transaction(function () use ($stock) {
            Stock::where('product_id', $stock->product_id)
                ->where('id', '!=', $stock->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            $stock->update(['is_active' => true]);
        });

        $stock->load('product');

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => "\"{$stock->product->name}\" batch \"{$stock->stockNo()}\" is now the active stock.",
                'stock'    => $this->formatStock($stock),
                'stocks'   => $this->allStocksFormatted(),
                'products' => $this->allProductsFormatted(),
            ]);
        }

        return redirect()->route('staff.stocks')->with('status', 'Stock activated successfully.');
    }

    // ── Products modal feeds ─────────────────────────────────────────────────

    /**
     * All products with an In Stock / Out of Stock status, for the
     * "Products" modal opened from the stocks page.
     */
    public function products(): JsonResponse
    {
        return response()->json(['products' => $this->allProductsFormatted()]);
    }

    /**
     * The batches of a single product that are available to become the
     * active batch (not already active, still has quantity, not expired),
     * longest time left before expiry first.
     */
    public function availableStocks(Product $product): JsonResponse
    {
        $today = now()->toDateString();

        $stocks = Stock::where('product_id', $product->id)
            ->where('is_active', false)
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($today) {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', $today);
            })
            ->orderByDesc('expiry_date')
            ->get()
            ->map(fn (Stock $stock) => $this->formatStock($stock))
            ->values();

        return response()->json([
            'product' => ['id' => $product->id, 'name' => $product->name],
            'stocks'  => $stocks,
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * All stock batches, newest first, formatted for the stocks table.
     */
    private function allStocksFormatted()
    {
        return Stock::with('product')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Stock $stock) => $this->formatStock($stock))
            ->values();
    }

    /**
     * All products with their In Stock / Out of Stock status, formatted for
     * the Products modal.
     */
    private function allProductsFormatted()
    {
        return Product::with(['stocks' => fn ($query) => $query->orderByDesc('expiry_date')])
            ->orderBy('name')
            ->get()
            ->map(function (Product $product) {
                $activeStock = $product->stocks->first(fn (Stock $stock) => $stock->isOngoingActive());

                return [
                    'id'          => $product->id,
                    'name'        => $product->name,
                    'image'       => $product->image ? asset($product->image) : null,
                    'status'      => $activeStock ? 'In Stock' : 'Out of Stock',
                    'quantity'    => $activeStock->quantity ?? 0,
                    'batch_count' => $product->stocks->count(),
                ];
            })
            ->values();
    }

    /**
     * Shape a Stock model into the flat array the stocks blade JS expects.
     */
    private function formatStock(Stock $stock): array
    {
        return [
            'id'                 => $stock->id,
            'stock_no'           => $stock->stockNo(),
            'product_id'         => $stock->product_id,
            'medicine'           => $stock->product->name ?? '—',
            'supplier'           => $stock->supplier,
            'quantity'           => $stock->quantity,
            'unit_cost'          => (float) $stock->unit_cost,
            'manufacturing_date' => optional($stock->manufacturing_date)->format('Y-m-d'),
            'expiry_date'        => optional($stock->expiry_date)->format('Y-m-d'),
            'is_active'          => $stock->is_active,
            'status'             => $stock->status,
            'added'              => optional($stock->created_at)->format('Y-m-d'),
        ];
    }

    /**
     * Whether the given product already has an ongoing active batch
     * (is_active, quantity > 0, not expired), optionally excluding one
     * batch by id (used when updating that same batch).
     */
    private function hasOngoingActiveStock(int $productId, ?int $excludeStockId = null): bool
    {
        return Stock::where('product_id', $productId)
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->where(function ($query) {
                $query->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()->toDateString());
            })
            ->when($excludeStockId, fn ($query) => $query->where('id', '!=', $excludeStockId))
            ->exists();
    }

    private function conflictResponse(Request $request): JsonResponse|RedirectResponse
    {
        $message = 'This product already has an ongoing active stock. Deactivate or use up that batch before activating another one.';

        if ($request->wantsJson()) {
            return response()->json(['message' => $message], 422);
        }

        return back()->with('error', $message)->withInput();
    }

    /**
     * Validate incoming stock form data against the stocks table columns.
     */
    private function validatedStockData(Request $request): array
    {
        return $request->validate([
            'product_id'         => ['required', 'integer', 'exists:products,id'],
            'supplier'           => ['nullable', 'string', 'max:255'],
            'quantity'           => ['required', 'integer', 'min:0'],
            'unit_cost'          => ['required', 'numeric', 'min:0'],
            'manufacturing_date' => ['nullable', 'date'],
            'expiry_date'        => ['nullable', 'date'],
            'is_active'          => ['nullable', 'boolean'],
        ]);
    }
}