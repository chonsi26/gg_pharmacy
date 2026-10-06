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
use App\Models\Setting;
use App\Models\FullWidthBanner;
use App\Models\PromoBanner;
use App\Models\Section;
use App\Models\Slider;
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
    $totalCustomers = User::count();
    $lowStockItems = Stock::where('is_active', 1)
        ->where('quantity', '<=', 10)
        ->count();
 
    return view('admin.index', compact('map_link', 'logo2', 'siteName', 'totalMedicines', 'totalCustomers', 'lowStockItems'));
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

    $staffs = Staff::orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    return view('admin.staffs', compact('logo2', 'siteName', 'staffs'));
}

    public function searchStaffs(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        $staffs = Staff::query()
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
        ->map(fn (Report $report) => $this->formatReport($report))
        ->values();

    return view('admin.reports', compact('logo2', 'siteName', 'reports'));
}

    public function storeReport(Request $request)
    {
        $data = $this->validatedReportData($request);

        $report = Report::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Report \"{$report->report_title}\" added successfully.",
                'report'  => $this->formatReport($report),
            ], 201);
        }

        return redirect()->route('admin.reports')
            ->with('status', "Report \"{$report->report_title}\" added successfully.");
    }

    public function updateReport(Request $request, Report $report)
    {
        $data = $this->validatedReportData($request);

        $report->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Report \"{$report->report_title}\" updated successfully.",
                'report'  => $this->formatReport($report),
            ]);
        }

        return redirect()->route('admin.reports')
            ->with('status', "Report \"{$report->report_title}\" updated successfully.");
    }

    public function destroyReport(Request $request, Report $report)
    {
        $title = $report->report_title;

        $report->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Report \"{$title}\" deleted successfully.",
            ]);
        }

        return redirect()->route('admin.reports')
            ->with('status', "Report \"{$title}\" deleted successfully.");
    }

    /**
     * Validate incoming report form data against the reports table columns.
     */
    private function validatedReportData(Request $request): array
    {
        return $request->validate([
            'report_title' => ['required', 'string', 'max:255'],
            'report_type'  => ['required', 'string', 'max:100'],
            'report_date'  => ['required', 'date'],
            'status'       => ['required', 'string', 'in:' . implode(',', Report::STATUSES)],
            'description'  => ['nullable', 'string'],
            'remarks'      => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Shape a Report model into the flat array the reports.blade.php table expects.
     */
    private function formatReport(Report $report): array
    {
        return [
            'id'          => $report->id,
            'title'       => $report->report_title,
            'type'        => $report->report_type,
            'date'        => optional($report->report_date)->format('Y-m-d'),
            'description' => $report->description,
            'status'      => $report->status,
            'remarks'     => $report->remarks,
        ];
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

    $logo2 = Setting::get('logo2');
    $siteName = Setting::get('site_name', 'Pharmacy');
    $fullWidthBanners = FullWidthBanner::orderBy('sort_order')
        ->get(['id', 'section_key', 'sort_order', 'image', 'alt', 'is_active'])
        ->map(fn (FullWidthBanner $banner) => $this->formatFullWidthBanner($banner))
        ->values();
    return view('admin.pharmacy_full_width_banners', compact('logo2', 'siteName', 'fullWidthBanners'));
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
    return view('admin.pharmacy_sliders', compact('logo2', 'siteName', 'sliders'));
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