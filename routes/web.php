<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminInventoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// ── Authentication ────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::post('/login',    [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Profile (auth only) ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',              [ProfileController::class, 'show'])           ->name('profile');
    Route::put('/profile',              [ProfileController::class, 'update'])         ->name('profile.update');
    Route::put('/profile/password',     [ProfileController::class, 'updatePassword']) ->name('profile.password');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {

    // Guest-only: login & register screens
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login',    [AdminController::class, 'showLogin'])->name('login');
        Route::post('/login',   [AdminController::class, 'login'])->name('login');

        Route::get('/register', [AdminController::class, 'showRegister'])->name('register');
        Route::post('/register',[AdminController::class, 'register'])->name('register');
    });

    Route::post('/logout', [AdminController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');

    // Auth-only: dashboard & inventory
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        // ── Inventory / Products (AdminInventoryController) ──────────────
        Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory');
        Route::post('/inventory', [AdminInventoryController::class, 'store'])->name('inventory.store');
        Route::put('/inventory/{product}', [AdminInventoryController::class, 'update'])->name('inventory.update');
        Route::put('/inventory/{product}/badge', [AdminInventoryController::class, 'updateBadge'])->name('inventory.badge');
        Route::delete('/inventory/{product}', [AdminInventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/staffs', [AdminController::class, 'staffs'])->name('staffs');
        Route::get('/staffs/search', [AdminController::class, 'searchStaffs'])->name('staffs.search');
        Route::post('/staffs', [AdminController::class, 'storeStaff'])->name('staffs.store');
        Route::put('/staffs/{staff}', [AdminController::class, 'updateStaff'])->name('staffs.update');
        Route::delete('/staffs/{staff}', [AdminController::class, 'destroyStaff'])->name('staffs.destroy');
        Route::get('/stocks', [AdminController::class, 'stocks'])->name('stocks');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::post('/reports', [AdminController::class, 'storeReport'])->name('reports.store');
        Route::put('/reports/{report}', [AdminController::class, 'updateReport'])->name('reports.update');
        Route::delete('/reports/{report}', [AdminController::class, 'destroyReport'])->name('reports.destroy');
        Route::get('/expiry', [AdminController::class, 'expiry'])->name('expiry');
        Route::get('/pharmacy', [AdminController::class, 'pharmacy'])->name('pharmacy');
        Route::put('/pharmacy/settings', [AdminController::class, 'updateSettings'])->name('pharmacy.settings.update');
        Route::get('/pharmacy_full_width_banners', [AdminController::class, 'pharmacy_full_width_banners'])->name('pharmacy_full_width_banners');
        Route::post('/pharmacy_full_width_banners', [AdminController::class, 'storeFullWidthBanner'])->name('pharmacy_full_width_banners.store');
        Route::put('/pharmacy_full_width_banners/{fullWidthBanner}', [AdminController::class, 'updateFullWidthBanner'])->name('pharmacy_full_width_banners.update');
        Route::delete('/pharmacy_full_width_banners/{fullWidthBanner}', [AdminController::class, 'destroyFullWidthBanner'])->name('pharmacy_full_width_banners.destroy');
        Route::get('/pharmacy_sections', [AdminController::class, 'pharmacy_sections'])->name('pharmacy_sections');
        Route::post('/pharmacy_sections', [AdminController::class, 'storeSection'])->name('pharmacy_sections.store');
        Route::put('/pharmacy_sections/{section}', [AdminController::class, 'updateSection'])->name('pharmacy_sections.update');
        Route::delete('/pharmacy_sections/{section}', [AdminController::class, 'destroySection'])->name('pharmacy_sections.destroy');
        Route::get('/pharmacy_promo_banners', [AdminController::class, 'pharmacy_promo_banners'])->name('pharmacy_promo_banners');
        Route::post('/pharmacy_promo_banners', [AdminController::class, 'storePromoBanner'])->name('pharmacy_promo_banners.store');
        Route::put('/pharmacy_promo_banners/{promoBanner}', [AdminController::class, 'updatePromoBanner'])->name('pharmacy_promo_banners.update');
        Route::delete('/pharmacy_promo_banners/{promoBanner}', [AdminController::class, 'destroyPromoBanner'])->name('pharmacy_promo_banners.destroy');
        Route::get('/pharmacy_sliders', [AdminController::class, 'pharmacy_sliders'])->name('pharmacy_sliders');
        Route::post('/pharmacy_sliders', [AdminController::class, 'storeSlider'])->name('pharmacy_sliders.store');
        Route::put('/pharmacy_sliders/{slider}', [AdminController::class, 'updateSlider'])->name('pharmacy_sliders.update');
        Route::delete('/pharmacy_sliders/{slider}', [AdminController::class, 'destroySlider'])->name('pharmacy_sliders.destroy');
        Route::get('/pharmacy_categories', [AdminController::class, 'pharmacy_categories'])->name('pharmacy_categories');
        Route::post('/pharmacy_categories', [AdminController::class, 'storeCategory'])->name('pharmacy_categories.store');
        Route::put('/pharmacy_categories/{category}', [AdminController::class, 'updateCategory'])->name('pharmacy_categories.update');
        Route::delete('/pharmacy_categories/{category}', [AdminController::class, 'destroyCategory'])->name('pharmacy_categories.destroy');
        Route::get('/pharmacy_brands', [AdminController::class, 'pharmacy_brands'])->name('pharmacy_brands');
        Route::post('/pharmacy_brands', [AdminController::class, 'storeBrand'])->name('pharmacy_brands.store');
        Route::put('/pharmacy_brands/{brand}', [AdminController::class, 'updateBrand'])->name('pharmacy_brands.update');
        Route::delete('/pharmacy_brands/{brand}', [AdminController::class, 'destroyBrand'])->name('pharmacy_brands.destroy');
        Route::get('/financials', [AdminController::class, 'financials'])->name('financials');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/photo', [AdminController::class, 'updatePhoto'])->name('profile.photo');
        Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });
});