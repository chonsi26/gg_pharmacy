<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Handles the staff inventory screen and all product (medicine) CRUD.
 *
 * Mirrors AdminInventoryController one-to-one so staff see and manage exactly
 * the same inventory. The only differences are the view (staffs.inventory)
 * and the route names used for non-JSON redirects (staff.inventory).
 *
 * Every write method answers JSON when the request asks for it (the blade
 * uses fetch + FormData), and falls back to a redirect for plain form posts.
 */
class StaffInventoryController extends Controller
{
    // ── Page ────────────────────────────────────────────────────────────────

    /**
     * Inventory listing. Same query as AdminInventoryController@index,
     * plus $sections so the Section dropdowns can be populated from the DB
     * instead of the hard-coded "Section A/B/C/D" options.
     */
    public function index(): View
    {
        $logo2    = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');

        $products = Product::with([
                'category',
                'brand',
                'section',
                'stocks' => function ($query) {
                    $query->where('is_active', true)->orderBy('id');
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        $brands     = Brand::orderBy('sort_order')->orderBy('name')->get();
        $sections   = Section::orderBy('sort_order')->orderBy('label')->get();

        return view('staffs.inventory', compact(
            'logo2', 'siteName', 'products', 'categories', 'brands', 'sections'
        ));
    }

    /**
     * Backs the "All Medicines" card search box. Matches on product name,
     * generic name, category name and brand name, and returns just the
     * matching product ids — the blade already has every card rendered, so
     * the JS only needs to know which cards to show/hide.
     */
    public function search(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        $ids = Product::query()
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($sub) use ($query) {
                    $sub->where('name', 'like', "%{$query}%")
                        ->orWhere('generic_name', 'like', "%{$query}%")
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$query}%"))
                        ->orWhereHas('brand', fn ($b) => $b->where('name', 'like', "%{$query}%"));
                });
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->pluck('id');

        return response()->json(['ids' => $ids]);
    }

    /**
     * Backs the "Sort by" chip on the inventory screen. Returns every
     * product id in the requested order — the blade already has every card
     * rendered, so the JS only needs to know what order to re-append them
     * in (same "just hand back ids" shape as search() above).
     *
     * Supported $request->query('by') values: "alphabetical", "date",
     * "category", "brand". Anything else falls back to the default listing
     * order (sort_order, then name) used by index().
     */
    public function sort(Request $request): JsonResponse
    {
        $by = $request->query('by', 'alphabetical');

        $query = Product::query();

        match ($by) {
            'alphabetical' => $query->orderBy('name'),
            'date'         => $query->orderByDesc('created_at'),
            'category'     => $query->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                                     ->orderByRaw('categories.name IS NULL')
                                     ->orderBy('categories.name')
                                     ->orderBy('products.name')
                                     ->select('products.*'),
            'brand'        => $query->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                                     ->orderByRaw('brands.name IS NULL')
                                     ->orderBy('brands.name')
                                     ->orderBy('products.name')
                                     ->select('products.*'),
            default        => $query->orderBy('sort_order')->orderBy('name'),
        };

        $ids = $query->pluck('products.id');

        return response()->json(['ids' => $ids, 'by' => $by]);
    }

    /**
     * Full details for the "view product" modal that opens when a card is
     * clicked. Returns everything the modal needs in one shot (including
     * fields like product_usage and stock quantity that aren't already
     * sitting in the card's data-* attributes) so the front end doesn't have
     * to guess or re-derive anything.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load([
            'category',
            'brand',
            'section',
            'stocks' => function ($query) {
                $query->where('is_active', true)->orderBy('id');
            },
        ]);

        return response()->json([
            'product' => $this->formatProductDetails($product),
        ]);
    }

    // ── Create ──────────────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $data = $this->validatedProductData($request);

        $data['section_id'] = $this->resolveSectionId($request->input('section_id'));

        // products.image is NOT NULL in the migration, so default to an empty
        // string — the blade already checks @if($product->image) before rendering.
        $data['image'] = $request->hasFile('image')
            ? $request->file('image')->store('products', 'public')
            : '';

        $data['requires_prescription'] = $request->boolean('requires_prescription');
        $data['is_active']             = $request->boolean('is_active', true);

        $swapped = null;

        if ($request->filled('sort_order')) {
            [$data['sort_order'], $swapped] = $this->resolveSortOrder(
                (int) $request->input('sort_order'),
                $data['section_id'],
                null
            );
        } else {
            $data['sort_order'] = $this->lowestFreeSortOrder($data['section_id']);
        }

        $product = Product::create($data);
        $product->load(['category', 'brand', 'section']);

        $message = $swapped
            ? "\"{$product->name}\" added to inventory. \"{$swapped->name}\" was moved to the end of that section to make room."
            : "\"{$product->name}\" added to inventory.";

        if ($request->wantsJson()) {
            return response()->json([
                'message'          => $message,
                'product'          => $this->formatProduct($product),
                'swapped_product'  => $swapped ? ['id' => $swapped->id, 'sort_order' => $swapped->sort_order] : null,
            ], 201);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    // ── Update ──────────────────────────────────────────────────────────────

    public function update(Request $request, Product $product)
    {
        $data = $this->validatedProductData($request, $product);

        // Only move the product to another section if one was actually sent.
        if ($request->filled('section_id')) {
            $data['section_id'] = $this->resolveSectionId($request->input('section_id'));
        }

        // Replace the image only when a new file is uploaded; otherwise keep
        // the existing one and leave the stored file alone.
        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $data['requires_prescription'] = $request->boolean('requires_prescription');
        $data['is_active']             = $request->boolean('is_active', (bool) $product->is_active);

        $swapped        = null;
        $sectionChanged = false;

        // Only touch sort_order if one was actually sent, and only swap when
        // something is actually changing — the number, the section, or both.
        if ($request->filled('sort_order')) {
            $requestedOrder  = (int) $request->input('sort_order');
            $targetSectionId = (int) ($data['section_id'] ?? $product->section_id);
            $sectionChanged  = $targetSectionId !== (int) $product->section_id;

            if ($requestedOrder !== (int) $product->sort_order || $sectionChanged) {
                // A true swap (handing the bumped product this product's old
                // number) only makes sense within the SAME section's
                // ordering. If the section is also changing, that old number
                // belongs to a different section's sequence and is
                // meaningless in the new one — so just push the bumped
                // product to the end of its own section instead, the same
                // way a brand-new product would be placed.
                [$data['sort_order'], $swapped] = $this->resolveSortOrder(
                    $requestedOrder,
                    $targetSectionId,
                    $sectionChanged ? null : $product
                );
            } else {
                unset($data['sort_order']);
            }
        }

        $product->update($data);
        $product->load(['category', 'brand', 'section']);

        $message = match (true) {
            $swapped && $sectionChanged => "\"{$product->name}\" moved and updated. \"{$swapped->name}\" was moved to the end of that section to make room.",
            (bool) $swapped             => "\"{$product->name}\" updated successfully. Swapped positions with \"{$swapped->name}\" in this section.",
            default                     => "\"{$product->name}\" updated successfully.",
        };

        if ($request->wantsJson()) {
            return response()->json([
                'message'          => $message,
                'product'          => $this->formatProduct($product),
                'swapped_product'  => $swapped ? ['id' => $swapped->id, 'sort_order' => $swapped->sort_order] : null,
            ]);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    /**
     * Update just the badge shown on the product card. Kept separate from
     * update() so the small badge modal doesn't have to resend the whole form.
     */
    public function updateBadge(Request $request, Product $product): JsonResponse
    {
        $data = $request->validate([
            'badge'      => ['nullable', 'string', 'max:40'],
            'badge_type' => ['nullable', 'string', 'in:sale-badge,most-sold'],
        ]);

        $product->update([
            'badge'      => $data['badge'] ?: null,
            'badge_type' => $data['badge'] ? ($data['badge_type'] ?? 'most-sold') : null,
        ]);

        return response()->json([
            'message'    => $product->badge
                ? "Badge \"{$product->badge}\" saved."
                : 'Badge removed.',
            'badge'      => $product->badge,
            'badge_type' => $product->badge_type,
        ]);
    }

    // ── Delete ──────────────────────────────────────────────────────────────

    public function destroy(Request $request, Product $product)
    {
        $name = $product->name;

        // Stock rows point at products.id — remove them first so the delete
        // isn't blocked by a foreign key constraint.
        $product->stocks()->delete();

        $this->deleteImage($product->image);
        $product->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "\"{$name}\" removed from inventory.",
                'id'      => $product->id,
            ]);
        }

        return redirect()->route('staff.inventory')
            ->with('status', "\"{$name}\" removed from inventory.");
    }

    // ── Categories ──────────────────────────────────────────────────────────

    /**
     * Create a new category from the inventory screen's "Add Category" modal.
     * New categories are appended to the end of the list — the modal doesn't
     * expose a sort_order field, so there's nothing for the user to conflict with.
     */
    public function storeCategory(Request $request)
    {
        $data = $this->validatedCategoryData($request);

        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = (int) Category::max('sort_order') + 1;

        $category = Category::create($data);

        $message = "Category \"{$category->name}\" added successfully.";

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => $message,
                'category' => $this->formatCategory($category),
            ], 201);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    /**
     * Update an existing category.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $data = $this->validatedCategoryData($request);

        $data['is_active'] = $request->boolean('is_active', (bool) $category->is_active);

        $category->update($data);

        $message = "Category \"{$category->name}\" updated successfully.";

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => $message,
                'category' => $this->formatCategory($category),
            ]);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    /**
     * Delete a category. Products already assigned to it (via category_id)
     * are left untouched — they'll just show as "Uncategorized" until
     * reassigned, same convention as removing a brand.
     */
    public function destroyCategory(Request $request, Category $category)
    {
        $name = $category->name;
        $id   = $category->id;

        $category->delete();

        $message = "Category \"{$name}\" deleted successfully.";

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'id'      => $id,
            ]);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    /**
     * Shared validation for storeCategory() and updateCategory(). icon_class,
     * icon_color and bg_color are NOT NULL columns but the modal allows an
     * empty icon_class, so they stay nullable here and just default to ''
     * when the field is left blank — that still satisfies the NOT NULL
     * constraint (empty string, not null).
     */
    private function validatedCategoryData(Request $request): array
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'icon_class' => ['nullable', 'string', 'max:255'],
            'icon_color' => ['nullable', 'string', 'max:20'],
            'bg_color'   => ['nullable', 'string', 'max:20'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $validated['icon_class'] = $validated['icon_class'] ?? '';
        $validated['icon_color'] = $validated['icon_color'] ?? '';
        $validated['bg_color']   = $validated['bg_color'] ?? '';

        return $validated;
    }

    /**
     * Shape a Category model the way the inventory blade's Categories modal
     * JS expects.
     */
    private function formatCategory(Category $category): array
    {
        return [
            'id'         => $category->id,
            'name'       => $category->name,
            'icon_class' => $category->icon_class,
            'icon_color' => $category->icon_color,
            'bg_color'   => $category->bg_color,
            'status'     => $category->is_active ? 'Active' : 'Inactive',
        ];
    }

    // ── Brands ──────────────────────────────────────────────────────────────

    /**
     * Create a new brand from the inventory screen's "Add Brand" modal. New
     * brands are appended to the end of the list — the modal doesn't expose
     * a sort_order field, so there's nothing for the user to conflict with.
     */
    public function storeBrand(Request $request)
    {
        $data = $this->validatedBrandData($request);

        if ($request->hasFile('ticker_image')) {
            $data['ticker_image'] = 'storage/' . $request->file('ticker_image')->store('brand_logos', 'public');
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = 'storage/' . $request->file('featured_image')->store('brand_logos', 'public');
        }

        $data['show_in_ticker']   = $request->boolean('show_in_ticker');
        $data['show_in_featured'] = $request->boolean('show_in_featured');
        $data['is_active']        = $request->boolean('is_active', true);
        $data['sort_order']       = (int) Brand::max('sort_order') + 1;

        $brand = Brand::create($data);

        $message = "Brand \"{$brand->name}\" added successfully.";

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'brand'   => $this->formatBrand($brand),
            ], 201);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    /**
     * Update an existing brand. Images are replaced only when a new file is
     * uploaded — otherwise the existing ticker/featured image is left alone,
     * same convention as product images in update() above.
     */
    public function updateBrand(Request $request, Brand $brand)
    {
        $data = $this->validatedBrandData($request);

        if ($request->hasFile('ticker_image')) {
            $this->deleteImage($brand->ticker_image);
            $data['ticker_image'] = 'storage/' . $request->file('ticker_image')->store('brand_logos', 'public');
        }

        if ($request->hasFile('featured_image')) {
            $this->deleteImage($brand->featured_image);
            $data['featured_image'] = 'storage/' . $request->file('featured_image')->store('brand_logos', 'public');
        }

        $data['show_in_ticker']   = $request->boolean('show_in_ticker', (bool) $brand->show_in_ticker);
        $data['show_in_featured'] = $request->boolean('show_in_featured', (bool) $brand->show_in_featured);
        $data['is_active']        = $request->boolean('is_active', (bool) $brand->is_active);

        $brand->update($data);

        $message = "Brand \"{$brand->name}\" updated successfully.";

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'brand'   => $this->formatBrand($brand),
            ]);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    /**
     * Delete a brand. Products already assigned to it (via brand_id) are
     * left untouched — they'll just show no brand until reassigned.
     */
    public function destroyBrand(Request $request, Brand $brand)
    {
        $name = $brand->name;
        $id   = $brand->id;

        $this->deleteImage($brand->ticker_image);
        $this->deleteImage($brand->featured_image);

        $brand->delete();

        $message = "Brand \"{$name}\" deleted successfully.";

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'id'      => $id,
            ]);
        }

        return redirect()->route('staff.inventory')->with('status', $message);
    }

    /**
     * Shared validation for storeBrand() and updateBrand(). Images and the
     * boolean flags are resolved separately by the caller.
     */
    private function validatedBrandData(Request $request): array
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'ticker_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'featured_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'featured_color'   => ['nullable', 'string', 'max:20'],
            'show_in_ticker'   => ['nullable', 'boolean'],
            'show_in_featured' => ['nullable', 'boolean'],
            'is_active'        => ['nullable', 'boolean'],
        ]);

        unset($validated['ticker_image'], $validated['featured_image']);

        return $validated;
    }

    /**
     * Shape a Brand model the way the inventory blade's Brands modal JS
     * expects.
     */
    private function formatBrand(Brand $brand): array
    {
        return [
            'id'               => $brand->id,
            'name'             => $brand->name,
            'ticker_image'     => $brand->ticker_image ? asset($brand->ticker_image) : null,
            'featured_image'   => $brand->featured_image ? asset($brand->featured_image) : null,
            'featured_color'   => $brand->featured_color,
            'show_in_ticker'   => (bool) $brand->show_in_ticker,
            'show_in_featured' => (bool) $brand->show_in_featured,
            'status'           => $brand->is_active ? 'Active' : 'Inactive',
        ];
    }

    /**
     * Everything formatProduct() has, plus the extra fields the read-only
     * details modal shows that the inventory cards don't already carry
     * (usage/directions, stock on hand, category & section display info,
     * and pre-formatted price/discount strings).
     */
    private function formatProductDetails(Product $product): array
    {
        $stock = $product->stocks->first();

        return array_merge($this->formatProduct($product), [
            'product_usage'       => $product->product_usage,
            'category_color'      => $product->category->icon_color ?? '#6b7280',
            'category_bg'         => $product->category->bg_color ?? '#f3f4f6',
            'section_id'          => $product->section_id,
            'section_label'       => $product->section->label ?? null,
            'stock_quantity'      => $stock->quantity ?? 0,
            'formatted_price'     => $product->formattedPrice(),
            'formatted_old_price' => $product->formattedOldPrice(),
            'has_discount'        => $product->hasDiscount(),
            'discount_percent'    => $product->discountPercent(),
            'has_dimensions'      => $product->hasDimensions(),
            'is_active'           => (bool) $product->is_active,
        ]);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Shared validation for store() and update(). Everything except name and
     * price is optional, which matches what the inventory modals actually ask for.
     */
    private function validatedProductData(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'generic_name'          => ['nullable', 'string', 'max:255'],
            'price'                 => ['required', 'numeric', 'min:0'],
            'old_price'             => ['nullable', 'numeric', 'min:0'],
            'category_id'           => ['nullable', 'integer', 'exists:categories,id'],
            'section_id'            => ['nullable', 'integer', 'exists:sections,id'],
            'brand_id'              => ['nullable', 'integer', 'exists:brands,id'],
            'origin'                => ['nullable', 'string', 'max:255'],
            'description'           => ['nullable', 'string'],
            'product_usage'         => ['nullable', 'string'],
            'ingredients'           => ['nullable', 'string'],
            'warnings'              => ['nullable', 'string'],
            'width'                 => ['nullable', 'numeric', 'min:0'],
            'height'                => ['nullable', 'numeric', 'min:0'],
            'depth'                 => ['nullable', 'numeric', 'min:0'],
            'requires_prescription' => ['nullable', 'boolean'],
            'is_active'             => ['nullable', 'boolean'],
            'sort_order'            => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'mimes:jpg,jpeg,png,webp,gif,avif', 'max:2048'],
        ]);

        // image / section_id are resolved separately by the caller.
        unset($validated['image'], $validated['section_id']);

        return $validated;
    }

    /**
     * products.section_id is NOT NULL, so fall back to the first section and
     * create a catch-all one if the table is still empty.
     */
    private function resolveSectionId($sectionId): int
    {
        if ($sectionId && Section::whereKey($sectionId)->exists()) {
            return (int) $sectionId;
        }

        $section = Section::orderBy('sort_order')->first();

        if (! $section) {
            $section = Section::create([
                'key'           => 'uncategorized',
                'label'         => 'Uncategorized',
                'heading_color' => 'red',
                'sort_order'    => 0,
                'is_active'     => true,
            ]);
        }

        return (int) $section->id;
    }

    /**
     * Smallest sort order (starting at 1) not already used within a section —
     * mirrors what the Add modal suggests, so a request that skips the field
     * entirely still fills the first open gap instead of always appending to
     * the end.
     */
    private function lowestFreeSortOrder(int $sectionId): int
    {
        $used = Product::where('section_id', $sectionId)
            ->pluck('sort_order')
            ->map(fn ($value) => (int) $value)
            ->flip();

        $candidate = 1;

        while ($used->has($candidate)) {
            $candidate++;
        }

        return $candidate;
    }

    /**
     * Assign a requested sort order within a section, swapping with whoever
     * currently holds that slot in the SAME section instead of leaving two
     * products on the same value — sort order is scoped per section, since
     * each section has its own independent ordering.
     *
     * - Editing: the bumped product takes the spot the saved product is
     *   leaving (a true swap).
     * - Creating: there's no "old spot" to give away yet, so the bumped
     *   product is moved to the next free slot at the end of that section.
     *
     * @return array{0: int, 1: ?Product} the resolved sort order, and the
     *         product that got bumped out of it (if any).
     */
    private function resolveSortOrder(int $requestedOrder, int $sectionId, ?Product $forProduct): array
    {
        $conflict = Product::where('sort_order', $requestedOrder)
            ->where('section_id', $sectionId)
            ->when($forProduct, fn ($query) => $query->where('id', '!=', $forProduct->id))
            ->first();

        if ($conflict) {
            $conflict->update([
                'sort_order' => $forProduct
                    ? $forProduct->sort_order
                    : ((int) Product::where('section_id', $sectionId)->max('sort_order') + 1),
            ]);
        }

        return [$requestedOrder, $conflict];
    }

    /**
     * Older rows may store the path with a "storage/" prefix; newer ones don't.
     */
    private function deleteImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete(Str::startsWith($path, 'storage/') ? Str::after($path, 'storage/') : $path);
    }

    /**
     * Shape a product the way the inventory card JS expects it.
     */
    private function formatProduct(Product $product): array
    {
        return [
            'id'                    => $product->id,
            'name'                  => $product->name,
            'generic_name'          => $product->generic_name,
            'brand_id'              => $product->brand_id,
            'brand_name'            => $product->brand->name ?? '',
            'category_id'           => $product->category_id,
            'category_name'         => $product->category->name ?? 'Uncategorized',
            'icon_color'            => $product->category->icon_color ?? '#6b7280',
            'bg_color'              => $product->category->bg_color ?? '#f3f4f6',
            'section_id'            => $product->section_id,
            'sort_order'            => $product->sort_order,
            'price'                 => (float) $product->price,
            'old_price'             => $product->old_price !== null ? (float) $product->old_price : null,
            'origin'                => $product->origin,
            'description'           => $product->description,
            'ingredients'           => $product->ingredients,
            'warnings'              => $product->warnings,
            'width'                 => $product->width,
            'height'                => $product->height,
            'depth'                 => $product->depth,
            'requires_prescription' => (bool) $product->requires_prescription,
            'badge'                 => $product->badge,
            'badge_type'            => $product->badge_type,
            'image_url'             => $product->image ? asset('storage/' . $product->image) : null,
        ];
    }
}