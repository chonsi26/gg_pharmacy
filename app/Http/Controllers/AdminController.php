<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use App\Models\Staff;
use App\Models\Report;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Setting;
use App\Models\FullWidthBanner;
use App\Models\CardImage;
use App\Models\PromoBanner;
use App\Models\Section;
use App\Models\Slider;
use App\Models\Media;
use App\Models\PaymentAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    // ── Dashboard ─────────────────────────────────────────────────────────────

    public function index(): View
{
    $map_link = Setting::get('map_link');
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $totalMedicines = Product::count();
    $lowStockItems = Stock::where('is_active', 1)
        ->where('quantity', '>', 0)
        ->where('quantity', '<=', 10)
        ->count();

    // Active batches with stock left that expire within the next 10 days
    // (today through today + 10). Already-expired batches are excluded.
    $expiringStocks = Stock::where('is_active', 1)
        ->where('quantity', '>', 0)
        ->whereNotNull('expiry_date')
        ->whereBetween('expiry_date', [now()->startOfDay(), now()->addDays(10)->endOfDay()])
        ->count();

    return view('admin.index', compact('map_link', 'logo2', 'siteName', 'totalMedicines', 'expiringStocks', 'lowStockItems'));
}

    // ── Inventory ────────────────────────────────────────────────────────────

    public function inventory(): View
    {
        $logo2 = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');

        // Each product, with its category, brand, and its first active stock
        // (used to display the current on-hand quantity on the card).
        $products = Product::with([
                'category',
                'brand',
                'stocks' => function ($query) {
                    $query->where('is_active', true)->orderBy('id');
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.inventory', compact('logo2', 'siteName', 'products', 'categories', 'brands'));
    }

        // ── Orders ─────────────────────────────────────────────────────────────

    public function orders(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    return view('admin.orders', compact('logo2', 'siteName'));
}

       // ── Staffs ─────────────────────────────────────────────────────────────

    public function staffs(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');

    // Pending sign-ups live in the Requests modal, not in the staff list.
    $staffs = Staff::registered()
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    return view('admin.staffs', compact('logo2', 'siteName', 'staffs'));
}

    public function searchStaffs(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $staffs = Staff::registered()
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($sub) use ($query) {
                    $sub->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('contact_number', 'like', "%{$query}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$query}%"]);
                });
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $avatarClasses = ['bg-staff-1', 'bg-staff-2', 'bg-staff-3', 'bg-staff-4', 'bg-staff-5', 'bg-staff-6'];

        $results = $staffs->values()->map(function ($staff, $index) use ($avatarClasses) {
            return [
                'db_id'        => $staff->id,
                'staff_code'   => 'STF-' . str_pad($staff->id, 4, '0', STR_PAD_LEFT),
                'first_name'   => $staff->first_name,
                'last_name'    => $staff->last_name,
                'email'        => $staff->email,
                'contact'      => $staff->contact_number,
                'address'      => $staff->address,
                'image'        => $staff->profile_picture ? asset($staff->profile_picture) : null,
                'status'       => $staff->is_active ? 'Active' : 'Inactive',
                'avatar_class' => $avatarClasses[$index % count($avatarClasses)],
            ];
        });

        return response()->json(['staffs' => $results]);
    }

    public function storeStaff(Request $request)
    {
        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:255', 'unique:staff,email'],
            'contact_number'  => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
            'password'        => ['required', 'string', Password::min(8)],
            'is_active'       => ['nullable', 'boolean'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'email.unique' => 'A staff account with this email already exists.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('staff_profile_pictures', 'public');
            $data['profile_picture'] = 'storage/' . $path;
        } else {
            unset($data['profile_picture']);
        }

        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);

        $staff = Staff::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Staff \"{$staff->first_name} {$staff->last_name}\" added successfully.",
                'staff'   => [
                    'id'               => $staff->id,
                    'staff_code'       => 'STF-' . str_pad($staff->id, 4, '0', STR_PAD_LEFT),
                    'first_name'       => $staff->first_name,
                    'last_name'        => $staff->last_name,
                    'email'            => $staff->email,
                    'contact_number'   => $staff->contact_number,
                    'address'          => $staff->address,
                    'is_active'        => $staff->is_active,
                    'profile_picture'  => $staff->profile_picture ? asset($staff->profile_picture) : null,
                ],
            ], 201);
        }

        return redirect()->route('admin.staffs')
            ->with('status', "Staff \"{$staff->first_name} {$staff->last_name}\" added successfully.");
    }

    public function updateStaff(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:255', 'unique:staff,email,' . $staff->id],
            'contact_number'  => ['nullable', 'string', 'max:20'],
            'address'         => ['nullable', 'string'],
            'password'        => ['nullable', 'string', Password::min(8)],
            'is_active'       => ['nullable', 'boolean'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'email.unique' => 'A staff account with this email already exists.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('staff_profile_pictures', 'public');
            $data['profile_picture'] = 'storage/' . $path;
        } else {
            unset($data['profile_picture']);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active', $staff->is_active);

        $staff->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Staff \"{$staff->first_name} {$staff->last_name}\" updated successfully.",
                'staff'   => [
                    'id'               => $staff->id,
                    'staff_code'       => 'STF-' . str_pad($staff->id, 4, '0', STR_PAD_LEFT),
                    'first_name'       => $staff->first_name,
                    'last_name'        => $staff->last_name,
                    'email'            => $staff->email,
                    'contact_number'   => $staff->contact_number,
                    'address'          => $staff->address,
                    'is_active'        => $staff->is_active,
                    'profile_picture'  => $staff->profile_picture ? asset($staff->profile_picture) : null,
                ],
            ]);
        }

        return redirect()->route('admin.staffs')
            ->with('status', "Staff \"{$staff->first_name} {$staff->last_name}\" updated successfully.");
    }

    public function destroyStaff(Request $request, Staff $staff)
    {
        $name = "{$staff->first_name} {$staff->last_name}";

        $staff->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Staff \"{$name}\" deleted successfully.",
            ]);
        }

        return redirect()->route('admin.staffs')
            ->with('status', "Staff \"{$name}\" deleted successfully.");
    }

       // ── Stocks ─────────────────────────────────────────────────────────────

    public function stocks(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');

    // Products for the Add/Edit Stock modal's medicine dropdown.
    $products = Product::active()->orderBy('name')->get(['id', 'name']);

    // All stock batches for the table, newest first.
    $stocks = AdminStocksController::formattedStocks();

    return view('admin.stocks', compact('logo2', 'siteName', 'products', 'stocks'));
}

       // ── Reports ─────────────────────────────────────────────────────────────

    public function reports(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');

    $reports = Report::orderByDesc('report_date')
        ->orderByDesc('id')
        ->get()
        ->map(fn (Report $report) => [
            'id'          => $report->id,
            'title'       => $report->report_title,
            'type'        => $report->report_type,
            'date'        => optional($report->report_date)->format('Y-m-d'),
            'description' => $report->description,
            'status'      => $report->status,
            'remarks'     => $report->remarks,
        ])
        ->values();

    return view('admin.reports', compact('logo2', 'siteName', 'reports'));
}

       // ── Expiry Tracking ─────────────────────────────────────────────────────────────

    public function expiry(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');

    // Every batch that has an expiry date, soonest-to-expire first, so the
    // most urgent batches are always what the admin sees at the top.
    $stocks = Stock::with('product:id,name,image')
        ->whereNotNull('expiry_date')
        ->orderBy('expiry_date', 'asc')
        ->get()
        ->map(fn (Stock $stock) => $this->formatExpiryStock($stock))
        ->values();

    return view('admin.expiry', compact('logo2', 'siteName', 'stocks'));
}

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

       // ── Pharmacy ─────────────────────────────────────────────────────────────

    public function pharmacy(): View
{
    Setting::dedupe();
    Setting::enforceRowLimit();
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $settings = Setting::allAsArray();
    return view('admin.pharmacy', compact('logo2', 'siteName', 'settings'));
}

       // ── Pharmacy Full Width Banner─────────────────────────────────────────────────────────────

    public function pharmacy_full_width_banners(): View
{
    // Self-heal in case rows ever landed in the table outside of Eloquent
    // (e.g. a manual SQL insert via MySQL Workbench/phpMyAdmin), which
    // would bypass the FullWidthBanner::created() model event entirely.
    // Running this on every page load guarantees the table never shows
    // more than MAX_BANNERS banners regardless of how a row got in.
    FullWidthBanner::pruneToMaxBanners();
    CardImage::pruneToMaxImages();

    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $fullWidthBanners = FullWidthBanner::orderBy('sort_order')
        ->get(['id', 'section_key', 'sort_order', 'image', 'alt', 'is_active'])
        ->map(fn (FullWidthBanner $banner) => $this->formatFullWidthBanner($banner))
        ->values();
    $cardImages = $this->allCardImagesFormatted();
    $blogs = Blog::with('category')->latest()->get()
        ->map(fn (Blog $blog) => $this->formatBlog($blog))
        ->values();
    $blogCategories = Category::orderBy('name')->get(['id', 'name'])
        ->map(fn (Category $c) => ['id' => $c->id, 'name' => $c->name])
        ->values();
    return view('admin.pharmacy_full_width_banners', compact('logo2', 'siteName', 'fullWidthBanners', 'cardImages', 'blogs', 'blogCategories'));
}

    /**
     * Create a new full-width banner. Limited to FullWidthBanner::MAX_BANNERS
     * total, and the section_key is always derived from the chosen sort_order.
     */
    public function storeFullWidthBanner(Request $request)
    {
        if (FullWidthBanner::count() >= FullWidthBanner::MAX_BANNERS) {
            $message = 'You can only have ' . FullWidthBanner::MAX_BANNERS . ' full-width banners at a time.';

            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $data = $this->validatedFullWidthBannerData($request);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('full_width_banners', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);

        $banner = new FullWidthBanner($data);
        $this->applyFullWidthBannerSortOrder($banner, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Full-width banner added successfully.',
                'banner'  => $this->formatFullWidthBanner($banner),
                'banners' => $this->allFullWidthBannersFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Full-width banner added successfully.');
    }

    /**
     * Update an existing full-width banner. Changing the sort_order
     * automatically re-derives the section_key (e.g. sort_order 2 -> fbanner_2).
     */
    public function updateFullWidthBanner(Request $request, FullWidthBanner $fullWidthBanner)
    {
        $data = $this->validatedFullWidthBannerData($request, $fullWidthBanner->id);

        if ($request->hasFile('image')) {
            if ($fullWidthBanner->image && Str::startsWith($fullWidthBanner->image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($fullWidthBanner->image, 'storage/'));
            }

            $path = $request->file('image')->store('full_width_banners', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', $fullWidthBanner->is_active);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);

        $fullWidthBanner->fill($data);
        $this->applyFullWidthBannerSortOrder($fullWidthBanner, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Full-width banner updated successfully.',
                'banner'  => $this->formatFullWidthBanner($fullWidthBanner),
                'banners' => $this->allFullWidthBannersFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Full-width banner updated successfully.');
    }

    public function destroyFullWidthBanner(Request $request, FullWidthBanner $fullWidthBanner)
    {
        if ($fullWidthBanner->image && Str::startsWith($fullWidthBanner->image, 'storage/')) {
            Storage::disk('public')->delete(Str::after($fullWidthBanner->image, 'storage/'));
        }

        $fullWidthBanner->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Full-width banner deleted successfully.']);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Full-width banner deleted successfully.');
    }

    /**
     * Validate incoming full-width banner form data. sort_order is
     * deliberately NOT required to be unique — a duplicate is allowed
     * through and resolved afterwards by applyFullWidthBannerSortOrder(),
     * which is what lets an admin "steal" another banner's slot and have
     * the two swap instead of seeing a validation error.
     */
    private function validatedFullWidthBannerData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'sort_order' => [
                'required',
                'integer',
                'min:1',
                'max:' . FullWidthBanner::MAX_BANNERS,
            ],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'alt'        => ['nullable', 'string', 'max:255'],
            'is_active'  => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Assign $desiredOrder to $banner and persist it. If another banner
     * already holds that slot, the two are swapped instead of the request
     * being rejected:
     *  - Editing (banner already has a sort_order): the occupant takes the
     *    slot the current banner is vacating — a straight swap.
     *  - Creating (banner has no sort_order yet): there's nothing to swap
     *    it back to, so the occupant is bumped to the next free slot.
     *
     * Both section_key and sort_order are unique at the database level, so
     * the swap is routed through an unused placeholder slot first —
     * otherwise the intermediate state of the swap would violate those
     * constraints. Everything happens inside a transaction so a failure
     * partway through never leaves two banners in the same slot.
     */
    private function applyFullWidthBannerSortOrder(FullWidthBanner $banner, int $desiredOrder): void
    {
        $currentOrder = $banner->sort_order;

        $occupant = FullWidthBanner::where('sort_order', $desiredOrder)
            ->when($banner->exists, fn ($query) => $query->where('id', '!=', $banner->id))
            ->first();

        if (!$occupant) {
            $banner->sort_order = $desiredOrder;
            $banner->save();
            return;
        }

        DB::transaction(function () use ($banner, $occupant, $desiredOrder, $currentOrder) {
            $placeholder = $this->nextUnusedFullWidthBannerSlot();
            $occupant->update(['sort_order' => $placeholder]);

            $banner->sort_order = $desiredOrder;
            $banner->save();

            if ($currentOrder === null) {
                $occupant->update(['sort_order' => $this->nextUnusedFullWidthBannerSlot()]);
            } else {
                $occupant->update(['sort_order' => $currentOrder]);
            }
        });
    }

    /**
     * The lowest sort_order value (starting at 0, so it never collides with
     * a real 1..MAX_BANNERS slot) not currently used by any full-width
     * banner. Used as a scratch slot while swapping two banners' orders.
     */
    private function nextUnusedFullWidthBannerSlot(): int
    {
        $used = FullWidthBanner::pluck('sort_order')->all();

        $candidate = 0;
        while (in_array($candidate, $used, true)) {
            $candidate++;
        }

        return $candidate;
    }

    /**
     * All full-width banners, ordered and formatted for the blade JS — used
     * to refresh the whole list any time an operation may have moved more
     * than one row (e.g. a sort_order swap).
     */
    private function allFullWidthBannersFormatted()
    {
        return FullWidthBanner::orderBy('sort_order')
            ->get(['id', 'section_key', 'sort_order', 'image', 'alt', 'is_active'])
            ->map(fn (FullWidthBanner $banner) => $this->formatFullWidthBanner($banner))
            ->values();
    }

    /**
     * Shape a FullWidthBanner model into the flat array the blade JS expects.
     */
    private function formatFullWidthBanner(FullWidthBanner $banner): array
    {
        return [
            'id'          => $banner->id,
            'section_key' => $banner->section_key,
            'sort_order'  => $banner->sort_order,
            'image'       => $this->fullWidthBannerImageUrl($banner->image),
            'alt'         => $banner->alt,
            'is_active'   => $banner->is_active,
        ];
    }

    /**
     * Resolve a banner's image column into a browser-usable URL. Uploaded
     * banners store a relative "storage/..." path and need asset(); seeded
     * or externally-hosted banners already store a full URL and must be
     * left untouched or asset() would mangle them.
     */
    private function fullWidthBannerImageUrl(?string $image): ?string
    {
        if (!$image) {
            return null;
        }

        return Str::startsWith($image, ['http://', 'https://']) ? $image : asset($image);
    }

       // ── Pharmacy Card Images (max 2) ─────────────────────────────────────────────────────

    /**
     * Create a new card image. Limited to CardImage::MAX_IMAGES total
     * (2 — the two tiles beside the dual-row product grids on the homepage).
     * The section_key is always derived from the chosen sort_order.
     */
    public function storeCardImage(Request $request)
    {
        if (CardImage::count() >= CardImage::MAX_IMAGES) {
            $message = 'You can only have ' . CardImage::MAX_IMAGES . ' card images at a time.';

            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $data = $this->validatedCardImageData($request);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('card_images', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);

        $card = new CardImage($data);
        $this->applyCardImageSortOrder($card, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => 'Card image added successfully.',
                'cardImage'  => $this->formatCardImage($card),
                'cardImages' => $this->allCardImagesFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Card image added successfully.');
    }

    /**
     * Update an existing card image. Changing the sort_order automatically
     * re-derives the section_key (e.g. sort_order 2 -> card_image_2).
     */
    public function updateCardImage(Request $request, CardImage $cardImage)
    {
        $data = $this->validatedCardImageData($request, $cardImage->id);

        if ($request->hasFile('image')) {
            if ($cardImage->image && Str::startsWith($cardImage->image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($cardImage->image, 'storage/'));
            }

            $path = $request->file('image')->store('card_images', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', $cardImage->is_active);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);

        $cardImage->fill($data);
        $this->applyCardImageSortOrder($cardImage, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => 'Card image updated successfully.',
                'cardImage'  => $this->formatCardImage($cardImage),
                'cardImages' => $this->allCardImagesFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Card image updated successfully.');
    }

    public function destroyCardImage(Request $request, CardImage $cardImage)
    {
        if ($cardImage->image && Str::startsWith($cardImage->image, 'storage/')) {
            Storage::disk('public')->delete(Str::after($cardImage->image, 'storage/'));
        }

        $cardImage->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Card image deleted successfully.']);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Card image deleted successfully.');
    }

    /**
     * Validate incoming card image form data. sort_order is deliberately NOT
     * required to be unique — a duplicate is resolved afterwards by
     * applyCardImageSortOrder(), which swaps the two cards.
     */
    private function validatedCardImageData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'sort_order' => [
                'required',
                'integer',
                'min:1',
                'max:' . CardImage::MAX_IMAGES,
            ],
            'image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'alt'       => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Assign $desiredOrder to $card and persist it. If another card already
     * holds that slot, the two are swapped (editing) or the occupant is
     * bumped to the next free slot (creating). Routed through an unused
     * placeholder slot inside a transaction so the unique constraints on
     * sort_order / section_key are never violated mid-swap.
     */
    private function applyCardImageSortOrder(CardImage $card, int $desiredOrder): void
    {
        $currentOrder = $card->sort_order;

        $occupant = CardImage::where('sort_order', $desiredOrder)
            ->when($card->exists, fn ($query) => $query->where('id', '!=', $card->id))
            ->first();

        if (!$occupant) {
            $card->sort_order = $desiredOrder;
            $card->save();
            return;
        }

        DB::transaction(function () use ($card, $occupant, $desiredOrder, $currentOrder) {
            $placeholder = $this->nextUnusedCardImageSlot();
            $occupant->update(['sort_order' => $placeholder]);

            $card->sort_order = $desiredOrder;
            $card->save();

            if ($currentOrder === null) {
                $occupant->update(['sort_order' => $this->nextUnusedCardImageSlot()]);
            } else {
                $occupant->update(['sort_order' => $currentOrder]);
            }
        });
    }

    /**
     * Lowest sort_order value (starting at 0, so it never collides with a
     * real 1..MAX_IMAGES slot) not used by any card image — a scratch slot
     * for swaps.
     */
    private function nextUnusedCardImageSlot(): int
    {
        $used = CardImage::pluck('sort_order')->all();

        $candidate = 0;
        while (in_array($candidate, $used, true)) {
            $candidate++;
        }

        return $candidate;
    }

    /** All card images, ordered and formatted for the blade JS. */
    private function allCardImagesFormatted()
    {
        return CardImage::orderBy('sort_order')
            ->get(['id', 'section_key', 'sort_order', 'image', 'alt', 'is_active'])
            ->map(fn (CardImage $card) => $this->formatCardImage($card))
            ->values();
    }

    /** Shape a CardImage model into the flat array the blade JS expects. */
    private function formatCardImage(CardImage $card): array
    {
        return [
            'id'          => $card->id,
            'section_key' => $card->section_key,
            'sort_order'  => $card->sort_order,
            'image'       => $this->fullWidthBannerImageUrl($card->image),
            'alt'         => $card->alt,
            'is_active'   => $card->is_active,
        ];
    }

       // ── Pharmacy Blogs (unlimited add / edit / delete) ─────────────────────────────────────

    /**
     * Create a blog post. There is no cap on how many blogs can exist.
     */
    public function storeBlog(Request $request)
    {
        $data = $this->validatedBlogData($request);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('blogs', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active']     = $request->boolean('is_active', true);
        $data['comment_count'] = (int) ($data['comment_count'] ?? 0);

        $blog = Blog::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Blog added successfully.',
                'blog'    => $this->formatBlog($blog->load('category')),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Blog added successfully.');
    }

    public function updateBlog(Request $request, Blog $blog)
    {
        $data = $this->validatedBlogData($request);

        if ($request->hasFile('image')) {
            $this->deleteBlogImage($blog);
            $path = $request->file('image')->store('blogs', 'public');
            $data['image'] = 'storage/' . $path;
        } elseif ($request->boolean('remove_image')) {
            $this->deleteBlogImage($blog);
            $data['image'] = null;
        } else {
            unset($data['image']);
        }

        $data['is_active']     = $request->boolean('is_active', $blog->is_active);
        $data['comment_count'] = (int) ($data['comment_count'] ?? 0);

        $blog->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Blog updated successfully.',
                'blog'    => $this->formatBlog($blog->load('category')),
            ]);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Blog updated successfully.');
    }

    public function destroyBlog(Request $request, Blog $blog)
    {
        $this->deleteBlogImage($blog);
        $blog->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Blog deleted successfully.']);
        }

        return redirect()->route('admin.pharmacy_full_width_banners')
            ->with('status', 'Blog deleted successfully.');
    }

    /**
     * Validate incoming blog form data. The month is normalised to upper case
     * first so "sep" and "Sep" both satisfy the 3-letter SEP, OCT … format.
     */
    private function validatedBlogData(Request $request): array
    {
        if ($request->has('month')) {
            $request->merge(['month' => strtoupper(trim((string) $request->input('month')))]);
        }

        return $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'excerpt'       => ['required', 'string', 'max:2000'],
            'image'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'icon_class'    => ['nullable', 'string', 'max:100'],
            'icon_color'    => ['nullable', 'string', 'max:50'],
            'icon_bg'       => ['nullable', 'string', 'max:50'],
            'day'           => ['required', 'integer', 'between:1,31'],
            'month'         => ['required', Rule::in(['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'])],
            'comment_count' => ['nullable', 'integer', 'min:0'],
            'category_id'   => ['nullable', 'integer', 'exists:categories,id'],
        ], [
            'title.required'   => 'Please enter a blog title.',
            'excerpt.required' => 'Please enter a short excerpt.',
        ]);
    }

    /** Remove a blog's uploaded image from disk (externally-hosted URLs are left alone). */
    private function deleteBlogImage(Blog $blog): void
    {
        if ($blog->image && Str::startsWith($blog->image, 'storage/')) {
            Storage::disk('public')->delete(Str::after($blog->image, 'storage/'));
        }
    }

    /** Shape a Blog model into the flat array the blade JS expects. */
    private function formatBlog(Blog $blog): array
    {
        return [
            'id'            => $blog->id,
            'title'         => $blog->title,
            'excerpt'       => $blog->excerpt,
            'image'         => $this->fullWidthBannerImageUrl($blog->image),
            'icon_class'    => $blog->icon_class,
            'icon_color'    => $blog->icon_color,
            'icon_bg'       => $blog->icon_bg,
            'day'           => $blog->day,
            'month'         => $blog->month,
            'comment_count' => $blog->comment_count,
            'category_id'   => $blog->category_id,
            'category_name' => $blog->category?->name,
            'is_active'     => $blog->is_active,
        ];
    }


       // ── Pharmacy Sections─────────────────────────────────────────────────────────────

    public function pharmacy_sections(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');

    $sections = Section::orderBy('sort_order')
        ->get(['id', 'key', 'label', 'description', 'heading_color', 'sort_order', 'is_active'])
        ->map(fn (Section $section) => $this->formatSection($section))
        ->values();

    return view('admin.pharmacy_sections', compact('logo2', 'siteName', 'sections'));
}

    /**
     * Create a new homepage section. The key is never entered by hand — it's
     * always derived from the chosen sort_order (e.g. sort_order 3 -> "section_3"),
     * so sort_order must be unique among sections for the derived key to stay
     * collision-free.
     */
    public function storeSection(Request $request)
    {
        $data = $this->validatedSectionData($request);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);
        $data['is_active'] = $request->boolean('is_active', true);

        $section = new Section($data);
        $this->applySectionSortOrder($section, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Section \"{$section->label}\" added successfully.",
                'section' => $this->formatSection($section),
                'sections' => $this->allSectionsFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_sections')
            ->with('status', "Section \"{$section->label}\" added successfully.");
    }

    /**
     * Update an existing homepage section. Changing the sort_order
     * automatically re-derives the key (e.g. sort_order 2 -> "section_2").
     */
    public function updateSection(Request $request, Section $section)
    {
        $data = $this->validatedSectionData($request, $section->id);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);

        $section->fill($data);
        $this->applySectionSortOrder($section, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Section \"{$section->label}\" updated successfully.",
                'section' => $this->formatSection($section),
                'sections' => $this->allSectionsFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_sections')
            ->with('status', "Section \"{$section->label}\" updated successfully.");
    }

    /**
     * Delete a homepage section. Products assigned to this section (via the
     * section_id foreign key) are left untouched by this action; consider
     * reassigning them first if the section still has products.
     */
    public function destroySection(Request $request, Section $section)
    {
        $label = $section->label;
        $section->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => "Section \"{$label}\" deleted successfully."]);
        }

        return redirect()->route('admin.pharmacy_sections')
            ->with('status', "Section \"{$label}\" deleted successfully.");
    }

    /**
     * Validate incoming section form data. sort_order is deliberately NOT
     * required to be unique — a duplicate is allowed through and resolved
     * afterwards by applySectionSortOrder(), which is what lets an admin
     * "steal" another section's slot and have the two swap instead of
     * seeing a validation error.
     */
    private function validatedSectionData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'label'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:255'],
            'heading_color' => ['required', 'string', 'max:20'],
            'sort_order'    => ['required', 'integer', 'min:1'],
        ]);
    }

    /**
     * Assign $desiredOrder to $section (deriving and assigning the matching
     * key) and persist it. If another section already holds that slot, the
     * two are swapped instead of the request being rejected:
     *  - Editing (section already has a sort_order): the occupant takes the
     *    slot the current section is vacating — a straight swap.
     *  - Creating (section has no sort_order yet): there's nothing to swap
     *    it back to, so the occupant is bumped to the next free slot.
     *
     * Both key and sort_order are unique at the database level, so the swap
     * is routed through an unused placeholder slot first — otherwise the
     * intermediate state of the swap would violate those constraints.
     * Everything happens inside a transaction so a failure partway through
     * never leaves two sections in the same slot (or with the same key).
     */
    private function applySectionSortOrder(Section $section, int $desiredOrder): void
    {
        $currentOrder = $section->sort_order;

        $occupant = Section::where('sort_order', $desiredOrder)
            ->when($section->exists, fn ($query) => $query->where('id', '!=', $section->id))
            ->first();

        if (!$occupant) {
            $section->sort_order = $desiredOrder;
            $section->key = Section::keyForSortOrder($desiredOrder);
            $section->save();
            return;
        }

        DB::transaction(function () use ($section, $occupant, $desiredOrder, $currentOrder) {
            $placeholder = $this->nextUnusedSectionSlot();
            $occupant->update([
                'sort_order' => $placeholder,
                'key'        => Section::keyForSortOrder($placeholder),
            ]);

            $section->sort_order = $desiredOrder;
            $section->key = Section::keyForSortOrder($desiredOrder);
            $section->save();

            $finalOccupantOrder = $currentOrder ?? $this->nextUnusedSectionSlot();

            $occupant->update([
                'sort_order' => $finalOccupantOrder,
                'key'        => Section::keyForSortOrder($finalOccupantOrder),
            ]);
        });
    }

    /**
     * The lowest sort_order value (starting at 0, so it never collides with
     * a real 1..N slot) not currently used by any section. Used as a
     * scratch slot while swapping two sections' orders.
     */
    private function nextUnusedSectionSlot(): int
    {
        $used = Section::pluck('sort_order')->all();

        $candidate = 0;
        while (in_array($candidate, $used, true)) {
            $candidate++;
        }

        return $candidate;
    }

    /**
     * All sections, ordered and formatted for the blade JS — used to
     * refresh the whole list any time an operation may have moved more
     * than one row (e.g. a sort_order swap).
     */
    private function allSectionsFormatted()
    {
        return Section::orderBy('sort_order')
            ->get(['id', 'key', 'label', 'description', 'heading_color', 'sort_order', 'is_active'])
            ->map(fn (Section $section) => $this->formatSection($section))
            ->values();
    }

    /**
     * Shape a Section model into the flat array the pharmacy_sections blade
     * JS expects.
     */
    private function formatSection(Section $section): array
    {
        return [
            'id'            => $section->id,
            'key'           => $section->key,
            'label'         => $section->label,
            'description'   => $section->description,
            'heading_color' => $section->heading_color,
            'sort_order'    => $section->sort_order,
            'is_active'     => $section->is_active,
        ];
    }

       // ── Pharmacy Promo Banners ─────────────────────────────────────────────────────────────

    public function pharmacy_promo_banners(): View
{
    // Self-heal in case rows already exceeded the 2-slot limit (bad seed,
    // manual DB edit, etc.) before this page ever gets viewed again.
    PromoBanner::pruneExcess();

    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $promoBanners = PromoBanner::orderBy('sort_order')
        ->get(['id', 'image', 'alt', 'type', 'sort_order', 'is_active'])
        ->map(fn (PromoBanner $banner) => $this->formatPromoBanner($banner))
        ->values();
    return view('admin.pharmacy_promo_banners', compact('logo2', 'siteName', 'promoBanners'));
}

    /**
     * Create a new promo banner. Limited to PromoBanner::MAX_BANNERS total
     * (currently 2, matching the two-tile sidebar layout on the homepage).
     */
    public function storePromoBanner(Request $request)
    {
        if (PromoBanner::count() >= PromoBanner::MAX_BANNERS) {
            $message = 'You can only have ' . PromoBanner::MAX_BANNERS . ' promo banners at a time.';

            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return back()->with('error', $message);
        }

        $data = $this->validatedPromoBannerData($request);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('promo_banners', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);

        $banner = new PromoBanner($data);
        $this->applyPromoBannerSortOrder($banner, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Promo banner added successfully.',
                'banner'  => $this->formatPromoBanner($banner),
                'banners' => $this->allPromoBannersFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_promo_banners')
            ->with('status', 'Promo banner added successfully.');
    }

    public function updatePromoBanner(Request $request, PromoBanner $promoBanner)
    {
        $data = $this->validatedPromoBannerData($request, $promoBanner->id);

        if ($request->hasFile('image')) {
            if ($promoBanner->image && Str::startsWith($promoBanner->image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($promoBanner->image, 'storage/'));
            }

            $path = $request->file('image')->store('promo_banners', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', $promoBanner->is_active);

        $sortOrder = (int) $data['sort_order'];
        unset($data['sort_order']);

        $promoBanner->fill($data);
        $this->applyPromoBannerSortOrder($promoBanner, $sortOrder);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Promo banner updated successfully.',
                'banner'  => $this->formatPromoBanner($promoBanner),
                'banners' => $this->allPromoBannersFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_promo_banners')
            ->with('status', 'Promo banner updated successfully.');
    }

    public function destroyPromoBanner(Request $request, PromoBanner $promoBanner)
    {
        if ($promoBanner->image && Str::startsWith($promoBanner->image, 'storage/')) {
            Storage::disk('public')->delete(Str::after($promoBanner->image, 'storage/'));
        }

        $promoBanner->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Promo banner deleted successfully.']);
        }

        return redirect()->route('admin.pharmacy_promo_banners')
            ->with('status', 'Promo banner deleted successfully.');
    }

    /**
     * Validate incoming promo banner form data. sort_order is deliberately
     * NOT required to be unique — a duplicate is allowed through and
     * resolved afterwards by applyPromoBannerSortOrder(), which is what
     * lets an admin "steal" another banner's slot and have the two swap
     * instead of seeing a validation error.
     */
    private function validatedPromoBannerData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'sort_order' => [
                'required',
                'integer',
                'min:1',
                'max:' . PromoBanner::MAX_BANNERS,
            ],
            'image'      => [$ignoreId ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'alt'        => ['nullable', 'string', 'max:255'],
            'type'       => ['required', 'string', 'in:red,beige'],
            'is_active'  => ['nullable', 'boolean'],
        ], [
            'image.required' => 'Please choose a banner image.',
        ]);
    }

    /**
     * Assign $desiredOrder to $banner and persist it. If another banner
     * already holds that slot, the two are swapped instead of the request
     * being rejected:
     *  - Editing (banner already has a sort_order): the occupant takes the
     *    slot the current banner is vacating — a straight swap.
     *  - Creating (banner has no sort_order yet): there's nothing to swap
     *    it back to, so the occupant is bumped to the next free slot.
     *
     * The swap is routed through an unused placeholder slot first so the
     * intermediate state never has two banners sharing the same sort_order,
     * even momentarily. Everything happens inside a transaction so a
     * failure partway through never leaves two banners in the same slot.
     */
    private function applyPromoBannerSortOrder(PromoBanner $banner, int $desiredOrder): void
    {
        $currentOrder = $banner->sort_order;

        $occupant = PromoBanner::where('sort_order', $desiredOrder)
            ->when($banner->exists, fn ($query) => $query->where('id', '!=', $banner->id))
            ->first();

        if (!$occupant) {
            $banner->sort_order = $desiredOrder;
            $banner->save();
            return;
        }

        DB::transaction(function () use ($banner, $occupant, $desiredOrder, $currentOrder) {
            $placeholder = $this->nextUnusedPromoBannerSlot();
            $occupant->update(['sort_order' => $placeholder]);

            $banner->sort_order = $desiredOrder;
            $banner->save();

            if ($currentOrder === null) {
                $occupant->update(['sort_order' => $this->nextUnusedPromoBannerSlot()]);
            } else {
                $occupant->update(['sort_order' => $currentOrder]);
            }
        });
    }

    /**
     * The lowest sort_order value (starting at 0, so it never collides with
     * a real 1..MAX_BANNERS slot) not currently used by any promo banner.
     * Used as a scratch slot while swapping two banners' orders.
     */
    private function nextUnusedPromoBannerSlot(): int
    {
        $used = PromoBanner::pluck('sort_order')->all();

        $candidate = 0;
        while (in_array($candidate, $used, true)) {
            $candidate++;
        }

        return $candidate;
    }

    /**
     * All promo banners, ordered and formatted for the blade JS — used to
     * refresh the whole list any time an operation may have moved more
     * than one row (e.g. a sort_order swap).
     */
    private function allPromoBannersFormatted()
    {
        return PromoBanner::orderBy('sort_order')
            ->get(['id', 'image', 'alt', 'type', 'sort_order', 'is_active'])
            ->map(fn (PromoBanner $banner) => $this->formatPromoBanner($banner))
            ->values();
    }

    /**
     * Shape a PromoBanner model into the flat array the blade JS expects.
     */
    private function formatPromoBanner(PromoBanner $banner): array
    {
        return [
            'id'         => $banner->id,
            'image'      => $this->fullWidthBannerImageUrl($banner->image),
            'alt'        => $banner->alt,
            'type'       => $banner->type,
            'sort_order' => $banner->sort_order,
            'is_active'  => $banner->is_active,
        ];
    }

       // ── Pharmacy Sliders ─────────────────────────────────────────────────────────────

    public function pharmacy_sliders(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $sliders = $this->allSlidersFormatted();
    $media = $this->allMediaFormatted();
    return view('admin.pharmacy_sliders', compact('logo2', 'siteName', 'sliders', 'media'));
}

    /**
     * Create a new homepage slider. sort_order does not have to be free —
     * if it's already used, reorderSlidersFor() bumps the other slider out
     * of the way instead of rejecting the request.
     */
    public function storeSlider(Request $request)
    {
        $data = $this->validatedSliderData($request);

        $this->reorderSlidersFor($data['sort_order']);

        $path = $request->file('image')->store('sliders', 'public');
        $data['image']     = 'storage/' . $path;
        $data['is_active'] = $request->boolean('is_active', true);

        $slider = Slider::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Slide added successfully.',
                'slider'  => $this->formatSlider($slider),
                'sliders' => $this->allSlidersFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_sliders')
            ->with('status', 'Slide added successfully.');
    }

    /**
     * Update an existing homepage slider. If the requested sort_order is
     * already held by a different slider, that slider is given this one's
     * old sort_order — a swap — rather than the request being rejected.
     */
    public function updateSlider(Request $request, Slider $slider)
    {
        $data = $this->validatedSliderData($request, $slider->id);

        if ((int) $data['sort_order'] !== (int) $slider->sort_order) {
            $this->reorderSlidersFor($data['sort_order'], $slider->id, (int) $slider->sort_order);
        }

        if ($request->hasFile('image')) {
            if ($slider->image && Str::startsWith($slider->image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($slider->image, 'storage/'));
            }

            $path = $request->file('image')->store('sliders', 'public');
            $data['image'] = 'storage/' . $path;
        }

        $data['is_active'] = $request->boolean('is_active', $slider->is_active);

        $slider->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Slide updated successfully.',
                'slider'  => $this->formatSlider($slider),
                'sliders' => $this->allSlidersFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_sliders')
            ->with('status', 'Slide updated successfully.');
    }

    public function destroySlider(Request $request, Slider $slider)
    {
        if ($slider->image && Str::startsWith($slider->image, 'storage/')) {
            Storage::disk('public')->delete(Str::after($slider->image, 'storage/'));
        }

        $slider->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Slide deleted successfully.',
                'sliders' => $this->allSlidersFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_sliders')
            ->with('status', 'Slide deleted successfully.');
    }

    /**
     * Validate incoming slider form data. Unlike other pharmacy sections,
     * sort_order is deliberately NOT required to be unique — a duplicate
     * is allowed through and resolved afterwards by reorderSlidersFor(),
     * which is what lets an admin "steal" another slide's position and
     * have the two swap instead of seeing a validation error.
     */
    private function validatedSliderData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'sort_order' => ['required', 'integer', 'min:1'],
            'image'      => [$ignoreId ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'alt'        => ['nullable', 'string', 'max:255'],
            'is_active'  => ['nullable', 'boolean'],
        ], [
            'image.required' => 'Please choose a slide image.',
        ]);
    }

    /**
     * If $desiredOrder is already held by another slider, free it up:
     *  - Editing (a $currentOldOrder is supplied): the other slider takes
     *    the sort_order the current slider is vacating — a straight swap.
     *  - Creating (no $currentOldOrder): there's nothing to swap it back
     *    to, so the other slider is bumped to the end of the list instead.
     */
    private function reorderSlidersFor(int $desiredOrder, ?int $currentId = null, ?int $currentOldOrder = null): void
    {
        $occupant = Slider::where('sort_order', $desiredOrder)
            ->when($currentId, fn ($query) => $query->where('id', '!=', $currentId))
            ->first();

        if (!$occupant) {
            return;
        }

        if ($currentOldOrder !== null) {
            $occupant->update(['sort_order' => $currentOldOrder]);
        } else {
            $occupant->update(['sort_order' => (Slider::max('sort_order') ?? 0) + 1]);
        }
    }

    /**
     * Shape a Slider model into the flat array the pharmacy_sliders blade
     * JS expects.
     */
    private function formatSlider(Slider $slider): array
    {
        return [
            'id'         => $slider->id,
            'image'      => $this->fullWidthBannerImageUrl($slider->image),
            'alt'        => $slider->alt,
            'sort_order' => $slider->sort_order,
            'is_active'  => $slider->is_active,
        ];
    }

    /**
     * All sliders, ordered and formatted for the blade JS — used to refresh
     * the whole list any time an operation may have moved more than one row
     * (e.g. a sort_order swap).
     */
    private function allSlidersFormatted()
    {
        return Slider::orderBy('sort_order')
            ->get(['id', 'image', 'alt', 'sort_order', 'is_active'])
            ->map(fn (Slider $slider) => $this->formatSlider($slider))
            ->values();
    }

       // ── Pharmacy Media (photos & videos) ─────────────────────────────────────────────────────

    private const MEDIA_VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov'];
    private const MEDIA_MAX_PHOTO_KB     = 5120;   // 5 MB
    private const MEDIA_MAX_VIDEO_KB     = 51200;  // 50 MB (also raise upload_max_filesize / post_max_size in php.ini)

    /**
     * Add a new photo or video of the pharmacy. Whether it is a photo or a
     * video is worked out from the uploaded file itself, never from a
     * client-supplied field. sort_order may already be taken — the other
     * item is bumped out of the way by reorderMediaFor().
     */
    public function storeMedia(Request $request)
    {
        $data = $this->validatedMediaData($request);

        $file = $request->file('file');
        $type = $this->mediaTypeOf($file);
        $this->assertMediaSizeAllowed($file, $type);

        $this->reorderMediaFor($data['sort_order']);

        $path = $file->store('media', 'public');

        $media = Media::create([
            'type'       => $type,
            'file'       => 'storage/' . $path,
            'title'      => $data['title'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_active'  => $request->boolean('is_active', true),
        ]);

        $label = $type === Media::TYPE_VIDEO ? 'Video' : 'Photo';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "{$label} added successfully.",
                'media'   => $this->formatMedia($media),
                'items'   => $this->allMediaFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_sliders')
            ->with('status', "{$label} added successfully.");
    }

    /**
     * Update a photo/video. Title, sort_order and active state can always be
     * changed; uploading a new file replaces the old one on disk (and the
     * item's type follows the new file, so a photo can be swapped for a video).
     */
    public function updateMedia(Request $request, Media $media)
    {
        $data = $this->validatedMediaData($request, $media->id);

        if ((int) $data['sort_order'] !== (int) $media->sort_order) {
            $this->reorderMediaFor($data['sort_order'], $media->id, (int) $media->sort_order);
        }

        $attributes = [
            'title'      => $data['title'] ?? null,
            'sort_order' => $data['sort_order'],
            'is_active'  => $request->boolean('is_active', $media->is_active),
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $type = $this->mediaTypeOf($file);
            $this->assertMediaSizeAllowed($file, $type);

            $this->deleteMediaFile($media->file);

            $attributes['type'] = $type;
            $attributes['file'] = 'storage/' . $file->store('media', 'public');
        }

        $media->update($attributes);

        $label = $media->type === Media::TYPE_VIDEO ? 'Video' : 'Photo';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "{$label} updated successfully.",
                'media'   => $this->formatMedia($media),
                'items'   => $this->allMediaFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_sliders')
            ->with('status', "{$label} updated successfully.");
    }

    public function destroyMedia(Request $request, Media $media)
    {
        $label = $media->type === Media::TYPE_VIDEO ? 'Video' : 'Photo';

        $this->deleteMediaFile($media->file);
        $media->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "{$label} deleted successfully.",
                'items'   => $this->allMediaFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_sliders')
            ->with('status', "{$label} deleted successfully.");
    }

    /**
     * Validate incoming media form data. The file is required when adding
     * and optional when editing. sort_order is intentionally not unique —
     * see reorderMediaFor().
     */
    private function validatedMediaData(Request $request, ?int $ignoreId = null): array
    {
        $maxKb = max(self::MEDIA_MAX_PHOTO_KB, self::MEDIA_MAX_VIDEO_KB);

        return $request->validate([
            'sort_order' => ['required', 'integer', 'min:1'],
            'file'       => [
                $ignoreId ? 'nullable' : 'required',
                'file',
                'mimes:jpg,jpeg,png,webp,mp4,webm,mov',
                'max:' . $maxKb,
            ],
            'title'      => ['nullable', 'string', 'max:255'],
            'is_active'  => ['nullable', 'boolean'],
        ], [
            'file.required' => 'Please choose a photo or video.',
            'file.mimes'    => 'Allowed files: JPG, PNG, WEBP photos and MP4, WEBM, MOV videos.',
            'file.max'      => 'That file is too large. Photos can be up to 5 MB and videos up to 50 MB.',
            'file.uploaded' => 'The upload failed — the file is probably larger than the server allows.',
        ]);
    }

    /** "photo" or "video", decided from the uploaded file's real extension. */
    private function mediaTypeOf(\Illuminate\Http\UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension());

        return in_array($ext, self::MEDIA_VIDEO_EXTENSIONS, true)
            ? Media::TYPE_VIDEO
            : Media::TYPE_PHOTO;
    }

    /** Photos have a tighter size cap than videos. */
    private function assertMediaSizeAllowed(\Illuminate\Http\UploadedFile $file, string $type): void
    {
        if ($type === Media::TYPE_PHOTO && $file->getSize() > self::MEDIA_MAX_PHOTO_KB * 1024) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'file' => 'Photos can be up to 5 MB. Please choose a smaller photo.',
            ]);
        }
    }

    private function deleteMediaFile(?string $path): void
    {
        if ($path && Str::startsWith($path, 'storage/')) {
            Storage::disk('public')->delete(Str::after($path, 'storage/'));
        }
    }

    /**
     * If $desiredOrder is already held by another item, free it up:
     *  - Editing: the other item takes the position the current one vacates.
     *  - Creating: the other item is bumped to the end of the list.
     */
    private function reorderMediaFor(int $desiredOrder, ?int $currentId = null, ?int $currentOldOrder = null): void
    {
        $occupant = Media::where('sort_order', $desiredOrder)
            ->when($currentId, fn ($query) => $query->where('id', '!=', $currentId))
            ->first();

        if (!$occupant) {
            return;
        }

        if ($currentOldOrder !== null) {
            $occupant->update(['sort_order' => $currentOldOrder]);
        } else {
            $occupant->update(['sort_order' => (Media::max('sort_order') ?? 0) + 1]);
        }
    }

    /** Shape a Media model into the flat array the blade JS expects. */
    private function formatMedia(Media $media): array
    {
        return [
            'id'         => $media->id,
            'type'       => $media->type,
            'file'       => $this->fullWidthBannerImageUrl($media->file),
            'title'      => $media->title,
            'sort_order' => $media->sort_order,
            'is_active'  => $media->is_active,
        ];
    }

    /** All media, ordered and formatted — refreshes the whole list after any change. */
    private function allMediaFormatted()
    {
        return Media::orderBy('sort_order')
            ->get(['id', 'type', 'file', 'title', 'sort_order', 'is_active'])
            ->map(fn (Media $media) => $this->formatMedia($media))
            ->values();
    }

       // ── Pharmacy Categories ─────────────────────────────────────────────────────────────

    public function pharmacy_categories(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $categories = $this->allCategoriesFormatted();
    return view('admin.pharmacy_categories', compact('logo2', 'siteName', 'categories'));
}

    /**
     * Create a new product category. sort_order does not have to be free —
     * if it's already used, reorderCategoriesFor() swaps it with the
     * occupying category instead of rejecting the request.
     */
    public function storeCategory(Request $request)
    {
        $data = $this->validatedCategoryData($request);

        $this->reorderCategoriesFor($data['sort_order']);

        $data['is_active'] = $request->boolean('is_active', true);

        $category = Category::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => "Category \"{$category->name}\" added successfully.",
                'category'   => $this->formatCategory($category),
                'categories' => $this->allCategoriesFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_categories')
            ->with('status', "Category \"{$category->name}\" added successfully.");
    }

    /**
     * Update an existing product category. If the requested sort_order is
     * already held by a different category, that category is given this
     * one's old sort_order — a swap — rather than the request being rejected.
     */
    public function updateCategory(Request $request, Category $category)
    {
        $data = $this->validatedCategoryData($request, $category->id);

        if ((int) $data['sort_order'] !== (int) $category->sort_order) {
            $this->reorderCategoriesFor($data['sort_order'], $category->id, (int) $category->sort_order);
        }

        $data['is_active'] = $request->boolean('is_active', $category->is_active);

        $category->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => "Category \"{$category->name}\" updated successfully.",
                'category'   => $this->formatCategory($category),
                'categories' => $this->allCategoriesFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_categories')
            ->with('status', "Category \"{$category->name}\" updated successfully.");
    }

    /**
     * Delete a product category. Products assigned to this category (via the
     * category_id foreign key) are left untouched by this action; consider
     * reassigning them first if the category still has products.
     */
    public function destroyCategory(Request $request, Category $category)
    {
        $name = $category->name;
        $category->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message'    => "Category \"{$name}\" deleted successfully.",
                'categories' => $this->allCategoriesFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_categories')
            ->with('status', "Category \"{$name}\" deleted successfully.");
    }

    /**
     * Validate incoming category form data. Unlike sections/banners,
     * sort_order is deliberately NOT required to be unique — a duplicate
     * is allowed through and resolved afterwards by reorderCategoriesFor(),
     * which is what lets an admin "steal" another category's position and
     * have the two swap instead of seeing a validation error.
     */
    private function validatedCategoryData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'icon_class'  => ['required', 'string', 'max:100'],
            'icon_color'  => ['required', 'string', 'max:20'],
            'bg_color'    => ['required', 'string', 'max:20'],
            'sort_order'  => ['required', 'integer', 'min:1'],
            'is_active'   => ['nullable', 'boolean'],
        ]);
    }

    /**
     * If $desiredOrder is already held by another category, free it up:
     *  - Editing (a $currentOldOrder is supplied): the other category takes
     *    the sort_order the current category is vacating — a straight swap.
     *  - Creating (no $currentOldOrder): there's nothing to swap it back
     *    to, so the other category is bumped to the end of the list instead.
     */
    private function reorderCategoriesFor(int $desiredOrder, ?int $currentId = null, ?int $currentOldOrder = null): void
    {
        $occupant = Category::where('sort_order', $desiredOrder)
            ->when($currentId, fn ($query) => $query->where('id', '!=', $currentId))
            ->first();

        if (!$occupant) {
            return;
        }

        if ($currentOldOrder !== null) {
            $occupant->update(['sort_order' => $currentOldOrder]);
        } else {
            $occupant->update(['sort_order' => (Category::max('sort_order') ?? 0) + 1]);
        }
    }

    /**
     * Shape a Category model into the flat array the pharmacy_categories
     * blade JS expects.
     */
    private function formatCategory(Category $category): array
    {
        return [
            'id'         => $category->id,
            'name'       => $category->name,
            'icon_class' => $category->icon_class,
            'icon_color' => $category->icon_color,
            'bg_color'   => $category->bg_color,
            'sort_order' => $category->sort_order,
            'is_active'  => $category->is_active,
        ];
    }

    /**
     * All categories, ordered and formatted for the blade JS — used to
     * refresh the whole list any time an operation may have moved more
     * than one row (e.g. a sort_order swap).
     */
    private function allCategoriesFormatted()
    {
        return Category::orderBy('sort_order')
            ->get(['id', 'name', 'icon_class', 'icon_color', 'bg_color', 'sort_order', 'is_active'])
            ->map(fn (Category $category) => $this->formatCategory($category))
            ->values();
    }

       // ── Pharmacy Brands ─────────────────────────────────────────────────────────────

    public function pharmacy_brands(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $brands = $this->allBrandsFormatted();
    return view('admin.pharmacy_brands', compact('logo2', 'siteName', 'brands'));
}

    /**
     * Create a new brand. sort_order does not have to be free — if it's
     * already used, reorderBrandsFor() swaps it with the occupying brand
     * instead of rejecting the request.
     */
    public function storeBrand(Request $request)
    {
        $data = $this->validatedBrandData($request);

        $this->reorderBrandsFor($data['sort_order']);

        if ($request->hasFile('ticker_image')) {
            $data['ticker_image'] = 'storage/' . $request->file('ticker_image')->store('brand_logos', 'public');
        } else {
            unset($data['ticker_image']);
        }

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = 'storage/' . $request->file('featured_image')->store('brand_logos', 'public');
        } else {
            unset($data['featured_image']);
        }

        $data['show_in_ticker']   = $request->boolean('show_in_ticker', true);
        $data['show_in_featured'] = $request->boolean('show_in_featured', false);
        $data['is_active']        = $request->boolean('is_active', true);

        $brand = Brand::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Brand \"{$brand->name}\" added successfully.",
                'brand'   => $this->formatBrand($brand),
                'brands'  => $this->allBrandsFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_brands')
            ->with('status', "Brand \"{$brand->name}\" added successfully.");
    }

    /**
     * Update an existing brand. If the requested sort_order is already held
     * by a different brand, that brand is given this one's old sort_order —
     * a swap — rather than the request being rejected.
     */
    public function updateBrand(Request $request, Brand $brand)
    {
        $data = $this->validatedBrandData($request, $brand->id);

        if ((int) $data['sort_order'] !== (int) $brand->sort_order) {
            $this->reorderBrandsFor($data['sort_order'], $brand->id, (int) $brand->sort_order);
        }

        if ($request->hasFile('ticker_image')) {
            if ($brand->ticker_image && Str::startsWith($brand->ticker_image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($brand->ticker_image, 'storage/'));
            }
            $data['ticker_image'] = 'storage/' . $request->file('ticker_image')->store('brand_logos', 'public');
        } else {
            unset($data['ticker_image']);
        }

        if ($request->hasFile('featured_image')) {
            if ($brand->featured_image && Str::startsWith($brand->featured_image, 'storage/')) {
                Storage::disk('public')->delete(Str::after($brand->featured_image, 'storage/'));
            }
            $data['featured_image'] = 'storage/' . $request->file('featured_image')->store('brand_logos', 'public');
        } else {
            unset($data['featured_image']);
        }

        $data['show_in_ticker']   = $request->boolean('show_in_ticker', $brand->show_in_ticker);
        $data['show_in_featured'] = $request->boolean('show_in_featured', $brand->show_in_featured);
        $data['is_active']        = $request->boolean('is_active', $brand->is_active);

        $brand->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Brand \"{$brand->name}\" updated successfully.",
                'brand'   => $this->formatBrand($brand),
                'brands'  => $this->allBrandsFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_brands')
            ->with('status', "Brand \"{$brand->name}\" updated successfully.");
    }

    /**
     * Delete a brand. Products assigned to this brand (via the brand_id
     * foreign key) are left untouched by this action; consider reassigning
     * them first if the brand still has products.
     */
    public function destroyBrand(Request $request, Brand $brand)
    {
        $name = $brand->name;

        foreach (['ticker_image', 'featured_image'] as $field) {
            if ($brand->$field && Str::startsWith($brand->$field, 'storage/')) {
                Storage::disk('public')->delete(Str::after($brand->$field, 'storage/'));
            }
        }

        $brand->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Brand \"{$name}\" deleted successfully.",
                'brands'  => $this->allBrandsFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_brands')
            ->with('status', "Brand \"{$name}\" deleted successfully.");
    }

    /**
     * Validate incoming brand form data. Unlike sections/banners, sort_order
     * is deliberately NOT required to be unique — a duplicate is allowed
     * through and resolved afterwards by reorderBrandsFor(), which is what
     * lets an admin "steal" another brand's position and have the two swap
     * instead of seeing a validation error.
     */
    private function validatedBrandData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'ticker_image'     => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'featured_image'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'featured_color'   => ['nullable', 'string', 'max:20'],
            'show_in_ticker'   => ['nullable', 'boolean'],
            'show_in_featured' => ['nullable', 'boolean'],
            'sort_order'       => ['required', 'integer', 'min:1'],
            'is_active'        => ['nullable', 'boolean'],
        ]);
    }

    /**
     * If $desiredOrder is already held by another brand, free it up:
     *  - Editing (a $currentOldOrder is supplied): the other brand takes the
     *    sort_order the current brand is vacating — a straight swap.
     *  - Creating (no $currentOldOrder): there's nothing to swap it back to,
     *    so the other brand is bumped to the end of the list instead.
     */
    private function reorderBrandsFor(int $desiredOrder, ?int $currentId = null, ?int $currentOldOrder = null): void
    {
        $occupant = Brand::where('sort_order', $desiredOrder)
            ->when($currentId, fn ($query) => $query->where('id', '!=', $currentId))
            ->first();

        if (!$occupant) {
            return;
        }

        if ($currentOldOrder !== null) {
            $occupant->update(['sort_order' => $currentOldOrder]);
        } else {
            $occupant->update(['sort_order' => (Brand::max('sort_order') ?? 0) + 1]);
        }
    }

    /**
     * Shape a Brand model into the flat array the pharmacy_brands blade JS
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
            'show_in_ticker'   => $brand->show_in_ticker,
            'show_in_featured' => $brand->show_in_featured,
            'sort_order'       => $brand->sort_order,
            'is_active'        => $brand->is_active,
        ];
    }

    /**
     * All brands, ordered and formatted for the blade JS — used to refresh
     * the whole list any time an operation may have moved more than one row
     * (e.g. a sort_order swap).
     */
    private function allBrandsFormatted()
    {
        return Brand::orderBy('sort_order')
            ->get(['id', 'name', 'ticker_image', 'featured_image', 'featured_color', 'show_in_ticker', 'show_in_featured', 'sort_order', 'is_active'])
            ->map(fn (Brand $brand) => $this->formatBrand($brand))
            ->values();
    }


    // ── Pharmacy Payment Accounts ─────────────────────────────────────────────

    public function pharmacy_payment_accounts(): View
    {
        $logo2 = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');
        $accounts = $this->allPaymentAccountsFormatted();
        return view('admin.pharmacy_payment_accounts', compact('logo2', 'siteName', 'accounts'));
    }

    /**
     * Create a payment account (GCash, Maya, ...). sort_order does not have
     * to be free — if it's taken, reorderPaymentAccountsFor() bumps the
     * occupying account to the end instead of rejecting the request.
     */
    public function storePaymentAccount(Request $request)
    {
        $data = $this->validatedPaymentAccountData($request);

        $this->reorderPaymentAccountsFor($data['sort_order']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('payment_accounts', 'public');
        } else {
            unset($data['image']);
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $account = PaymentAccount::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => "Payment account \"{$account->payment_app}\" added successfully.",
                'account'  => $this->formatPaymentAccount($account),
                'accounts' => $this->allPaymentAccountsFormatted(),
            ], 201);
        }

        return redirect()->route('admin.pharmacy_payment_accounts')
            ->with('status', "Payment account \"{$account->payment_app}\" added successfully.");
    }

    /**
     * Update a payment account. If the requested sort_order is held by a
     * different account, the two swap positions.
     */
    public function updatePaymentAccount(Request $request, PaymentAccount $paymentAccount)
    {
        $data = $this->validatedPaymentAccountData($request, $paymentAccount->id);

        if ((int) $data['sort_order'] !== (int) $paymentAccount->sort_order) {
            $this->reorderPaymentAccountsFor($data['sort_order'], $paymentAccount->id, (int) $paymentAccount->sort_order);
        }

        if ($request->hasFile('image')) {
            if ($paymentAccount->image) {
                Storage::disk('public')->delete($paymentAccount->image);
            }
            $data['image'] = $request->file('image')->store('payment_accounts', 'public');
        } else {
            unset($data['image']);
        }

        $data['is_active'] = $request->boolean('is_active', $paymentAccount->is_active);

        $paymentAccount->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => "Payment account \"{$paymentAccount->payment_app}\" updated successfully.",
                'account'  => $this->formatPaymentAccount($paymentAccount),
                'accounts' => $this->allPaymentAccountsFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_payment_accounts')
            ->with('status', "Payment account \"{$paymentAccount->payment_app}\" updated successfully.");
    }

    /**
     * Delete a payment account and its uploaded screenshot.
     */
    public function destroyPaymentAccount(Request $request, PaymentAccount $paymentAccount)
    {
        $name = $paymentAccount->payment_app;

        if ($paymentAccount->image) {
            Storage::disk('public')->delete($paymentAccount->image);
        }

        $paymentAccount->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => "Payment account \"{$name}\" deleted successfully.",
                'accounts' => $this->allPaymentAccountsFormatted(),
            ]);
        }

        return redirect()->route('admin.pharmacy_payment_accounts')
            ->with('status', "Payment account \"{$name}\" deleted successfully.");
    }

    /**
     * Validate payment account form data. sort_order is deliberately not
     * unique — a duplicate is resolved by reorderPaymentAccountsFor().
     */
    private function validatedPaymentAccountData(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'payment_app'    => ['required', 'string', 'max:255'],
            'account_name'   => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:255'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'sort_order'     => ['required', 'integer', 'min:1'],
            'is_active'      => ['nullable', 'boolean'],
        ]);
    }

    /**
     * If $desiredOrder is already held by another account, free it up:
     *  - Editing ($currentOldOrder supplied): the occupant takes the
     *    sort_order the current account is vacating — a straight swap.
     *  - Creating: the occupant is bumped to the end of the list.
     */
    private function reorderPaymentAccountsFor(int $desiredOrder, ?int $currentId = null, ?int $currentOldOrder = null): void
    {
        $occupant = PaymentAccount::where('sort_order', $desiredOrder)
            ->when($currentId, fn ($query) => $query->where('id', '!=', $currentId))
            ->first();

        if (!$occupant) {
            return;
        }

        if ($currentOldOrder !== null) {
            $occupant->update(['sort_order' => $currentOldOrder]);
        } else {
            $occupant->update(['sort_order' => (PaymentAccount::max('sort_order') ?? 0) + 1]);
        }
    }

    /**
     * Shape a PaymentAccount into the flat array the payment accounts blade
     * JS expects.
     */
    private function formatPaymentAccount(PaymentAccount $account): array
    {
        return [
            'id'             => $account->id,
            'payment_app'    => $account->payment_app,
            'account_name'   => $account->account_name,
            'account_number' => $account->account_number,
            'image'          => $account->image ? asset('storage/' . $account->image) : null,
            'sort_order'     => $account->sort_order,
            'is_active'      => $account->is_active,
        ];
    }

    /**
     * All payment accounts, ordered and formatted — used to refresh the
     * whole list after any operation that may have moved more than one row.
     */
    private function allPaymentAccountsFormatted()
    {
        return PaymentAccount::ordered()
            ->get()
            ->map(fn (PaymentAccount $account) => $this->formatPaymentAccount($account))
            ->values();
    }

    /**
     * Persist the general site settings (key/value pairs) edited from the
     * pharmacy settings panel.
     */
    public function updateSettings(Request $request): JsonResponse
    {
        // Logos are real file uploads (multipart), NOT base64 strings in the
        // settings payload. Base64 images overflow the TEXT column (64 KB) and
        // can't be rendered by asset() in the layout.
        $logoKeys = ['logo', 'logo2'];

        $data = $request->validate([
            'settings'     => ['nullable', 'array'],
            'settings.*'   => ['nullable', 'string'],
            'logos'        => ['nullable', 'array'],
            'logos.logo'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
            'logos.logo2'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:2048'],
        ], [
            'logos.*.image' => 'The logo must be an image.',
            'logos.*.mimes' => 'Allowed logo formats: JPG, PNG, WEBP, GIF.',
            'logos.*.max'   => 'Each logo must not exceed 2 MB.',
        ]);

        Setting::dedupe();

        // Plain text settings — never let the logo keys be overwritten by text.
        $textSettings = array_diff_key($data['settings'] ?? [], array_flip($logoKeys));

        foreach ($textSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Uploaded logo files.
        foreach ($logoKeys as $key) {
            $file = $request->file("logos.$key");

            if (!$file) {
                continue;
            }

            $old = Setting::get($key);
            $path = $file->store('settings', 'public');

            Setting::updateOrCreate(['key' => $key], ['value' => 'storage/' . $path]);

            // Clean up the previous upload (only files we stored ourselves).
            if ($old && Str::startsWith($old, 'storage/settings/')) {
                Storage::disk('public')->delete(Str::after($old, 'storage/'));
            }
        }

        Setting::enforceRowLimit();

        return response()->json([
            'message'  => 'Updated Successfully',
            'settings' => Setting::allAsArray(),
        ]);
    }

       // ── Financial Records ─────────────────────────────────────────────────────────────

    public function financials(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    return view('admin.financials', compact('logo2', 'siteName'));
}

       // ── Settings ─────────────────────────────────────────────────────────────

    public function settings(): View
{
    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    return view('admin.settings', compact('logo2', 'siteName'));
}



       // ── Profile ─────────────────────────────────────────────────────────────

    public function profile(): View
{
    $logo2 = Setting::get('logo2');
    $address_line1 = Setting::get('address_line1');
    $address_line2 = Setting::get('address_line2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    return view('admin.profile', compact('logo2', 'siteName', 'address_line1', 'address_line2'));
}

    /**
     * Update the currently authenticated admin's account details.
     */
    public function updateProfile(Request $request): RedirectResponse|JsonResponse
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'first_name'   => ['required', 'string', 'max:100'],
            'middle_name'  => ['nullable', 'string', 'max:100'],
            'last_name'    => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:255', 'unique:admins,email,' . $admin->id],
            'phone_number' => ['nullable', 'regex:/^9\d{9}$/'],
        ], [
            'email.unique'       => 'An account with this email already exists.',
            'phone_number.regex' => 'Enter a valid 10-digit PH mobile number (e.g. 9123456789).',
        ]);

        if (!empty($data['phone_number'])) {
            $data['phone_number'] = '+63' . $data['phone_number'];
        } else {
            $data['phone_number'] = null;
        }

        $admin->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Profile information updated.',
                'admin'   => [
                    'first_name'   => $admin->first_name,
                    'middle_name'  => $admin->middle_name,
                    'last_name'    => $admin->last_name,
                    'email'        => $admin->email,
                    'phone_number' => $admin->phone_number,
                ],
            ]);
        }

        return back()->with('status', 'Profile information updated.');
    }

    /**
     * Update the currently authenticated admin's profile photo.
     */
    public function updatePhoto(Request $request): JsonResponse
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'profile_picture.required' => 'Please choose a photo to upload.',
            'profile_picture.image'    => 'The file must be an image.',
            'profile_picture.mimes'    => 'Allowed formats: JPG, PNG, WEBP.',
            'profile_picture.max'      => 'The photo must not exceed 2 MB.',
        ]);

        // Remove the old file from disk (if one exists and lives on the public disk).
        if ($admin->profile_picture && Str::startsWith($admin->profile_picture, 'storage/')) {
            Storage::disk('public')->delete(Str::after($admin->profile_picture, 'storage/'));
        }

        $path = $request->file('profile_picture')->store('admin_profile_pictures', 'public');

        $admin->update(['profile_picture' => 'storage/' . $path]);

        return response()->json([
            'message'          => 'Profile photo updated.',
            'profile_picture'  => asset($admin->profile_picture),
        ]);
    }

    /**
     * Update the currently authenticated admin's password.
     */
    public function updatePassword(Request $request): RedirectResponse|JsonResponse
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.confirmed' => 'The passwords do not match.',
        ]);

        if (!Hash::check($data['current_password'], $admin->password)) {
            $errors = ['current_password' => ['The current password is incorrect.']];

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'The current password is incorrect.',
                    'errors'  => $errors,
                ], 422);
            }

            return back()
                ->withErrors($errors)
                ->withFragment('security');
        }

        $admin->update(['password' => Hash::make($data['password'])]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Security settings updated.']);
        }

        return back()->with('status', 'Security settings updated.');
    }

    // ── Show forms ───────────────────────────────────────────────────────────

public function showLogin(): View
{
    $logo = Setting::get('logo');
    $address_line1 = Setting::get('address_line1');
    $address_line2 = Setting::get('address_line2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    return view('admin.login', compact('logo' ,'siteName', 'address_line1', 'address_line2'));
}

    public function showRegister(): View
    {
        $logo = Setting::get('logo');
        $address_line1 = Setting::get('address_line1');
        $address_line2 = Setting::get('address_line2');
        $siteName = Setting::get('site_name', 'Pharmacy');
        return view('admin.register', compact('logo' ,'siteName', 'address_line1', 'address_line2'));
    }

    // ── Login ─────────────────────────────────────────────────────────────────

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginField => $request->input('login'),
            'password'  => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()
            ->withErrors(['login' => 'These credentials do not match our records.'])
            ->withInput($request->only('login'))
            ->withFragment('card');
    }

    // ── Register ──────────────────────────────────────────────────────────────

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:100'],
            'middle_name'     => ['nullable', 'string', 'max:100'],
            'last_name'       => ['required', 'string', 'max:100'],
            'email'           => ['required', 'email', 'max:255', 'unique:admins,email'],
            'phone_number'    => ['required', 'regex:/^9\d{9}$/'],
            'username'        => ['required', 'string', 'max:50', 'alpha_dash', 'unique:admins,username'],
            'password'        => ['required', 'confirmed', Password::min(8)],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'email.unique'         => 'An account with this email already exists.',
            'username.unique'      => 'This username is already taken.',
            'phone_number.regex'   => 'Enter a valid 10-digit PH mobile number (e.g. 9123456789).',
            'password.confirmed'   => 'The passwords do not match.',
            'profile_picture.image'=> 'The profile photo must be an image.',
            'profile_picture.max'  => 'The profile photo must not exceed 2 MB.',
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('admin_profile_pictures', 'public');
            $data['profile_picture'] = 'storage/' . $path;
        } else {
            unset($data['profile_picture']);
        }

        $data['phone_number'] = '+63' . $data['phone_number'];
        $data['password'] = Hash::make($data['password']);

        $admin = Admin::create($data);

        Auth::guard('admin')->login($admin);

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('status', 'Account created! Welcome to Pharmacy.');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('status', 'You have been logged out.');
    }
}