<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminFinancialsController;
use App\Http\Controllers\AdminInventoryController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AdminStocksController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

// ── Cart (AJAX; guests get a 401 + {status:'guest'} instead of a redirect) ─────
Route::post('/cart/add',   [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count',  [CartController::class, 'count'])->name('cart.count');

// ── Cart page (view + manage items; requires login — guests never have rows) ──
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    // Must be registered before '/cart/{cart}' so "selected" isn't swallowed
    // by the {cart} route-model-binding wildcard.
    Route::delete('/cart/selected', [CartController::class, 'destroySelected'])->name('cart.destroySelected');
    Route::put('/cart/{cart}',      [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}',   [CartController::class, 'destroy'])->name('cart.destroy');
});

// ── Orders (auth only; "Buy Now" and cart "Checkout" both land here) ──────────
Route::middleware('auth')->group(function () {
    Route::post('/order/buy-now',  [OrderController::class, 'reviewBuyNow'])->name('order.buyNow');
    Route::post('/order/checkout', [OrderController::class, 'reviewCheckout'])->name('order.checkout');
    Route::get('/orders',          [OrderController::class, 'index'])->name('order.myorders');
    Route::post('/orders',         [OrderController::class, 'store'])->name('order.store');
    Route::post('/order/review/discard', [OrderController::class, 'discardReview'])->name('order.review.discard');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('order.track');
    Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('order.cancel');
    Route::post('/orders/{order}/proof-of-payment', [OrderController::class, 'uploadProofOfPayment'])->name('order.proof.upload');
    Route::post('/orders/items/{orderItem}/prescription', [OrderController::class, 'uploadPrescription'])->name('order.prescription.upload');
});

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
        Route::get('/inventory/search', [AdminInventoryController::class, 'search'])->name('inventory.search');
        Route::get('/inventory/sort', [AdminInventoryController::class, 'sort'])->name('inventory.sort');
        Route::get('/inventory/{product}', [AdminInventoryController::class, 'show'])->name('inventory.show');
        Route::post('/inventory', [AdminInventoryController::class, 'store'])->name('inventory.store');
        Route::put('/inventory/{product}', [AdminInventoryController::class, 'update'])->name('inventory.update');
        Route::put('/inventory/{product}/badge', [AdminInventoryController::class, 'updateBadge'])->name('inventory.badge');
        Route::delete('/inventory/{product}', [AdminInventoryController::class, 'destroy'])->name('inventory.destroy');
        // ── Categories (managed from the inventory screen's Category modal) ──
        Route::post('/inventory/categories', [AdminInventoryController::class, 'storeCategory'])->name('inventory.categories.store');
        Route::put('/inventory/categories/{category}', [AdminInventoryController::class, 'updateCategory'])->name('inventory.categories.update');
        Route::delete('/inventory/categories/{category}', [AdminInventoryController::class, 'destroyCategory'])->name('inventory.categories.destroy');
        // ── Brands (managed from the inventory screen's Brand modal) ──────
        Route::post('/inventory/brands', [AdminInventoryController::class, 'storeBrand'])->name('inventory.brands.store');
        Route::put('/inventory/brands/{brand}', [AdminInventoryController::class, 'updateBrand'])->name('inventory.brands.update');
        Route::delete('/inventory/brands/{brand}', [AdminInventoryController::class, 'destroyBrand'])->name('inventory.brands.destroy');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        // ── Orders (AdminOrdersController) ───────────────────────────────
        Route::get('/orders/data', [AdminOrdersController::class, 'data'])->name('orders.data');
        Route::put('/orders/{order}/confirm', [AdminOrdersController::class, 'confirm'])->name('orders.confirm');
        Route::put('/orders/{order}/ready', [AdminOrdersController::class, 'ready'])->name('orders.ready');
        Route::put('/orders/{order}/complete', [AdminOrdersController::class, 'complete'])->name('orders.complete');
        Route::put('/orders/{order}/cancel', [AdminOrdersController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/refund', [AdminOrdersController::class, 'refund'])->name('orders.refund');
        Route::delete('/orders/{order}', [AdminOrdersController::class, 'destroy'])->name('orders.destroy');
        Route::get('/staffs', [AdminController::class, 'staffs'])->name('staffs');
        Route::get('/staffs/search', [AdminController::class, 'searchStaffs'])->name('staffs.search');
        Route::post('/staffs', [AdminController::class, 'storeStaff'])->name('staffs.store');
        Route::put('/staffs/{staff}', [AdminController::class, 'updateStaff'])->name('staffs.update');
        Route::delete('/staffs/{staff}', [AdminController::class, 'destroyStaff'])->name('staffs.destroy');
        Route::get('/stocks', [AdminController::class, 'stocks'])->name('stocks');
        Route::post('/stocks', [AdminStocksController::class, 'store'])->name('stocks.store');
        Route::put('/stocks/{stock}', [AdminStocksController::class, 'update'])->name('stocks.update');
        Route::delete('/stocks/{stock}', [AdminStocksController::class, 'destroy'])->name('stocks.destroy');
        Route::put('/stocks/{stock}/activate', [AdminStocksController::class, 'activate'])->name('stocks.activate');
        Route::get('/stocks/products', [AdminStocksController::class, 'products'])->name('stocks.products');
        Route::get('/stocks/products/{product}/available', [AdminStocksController::class, 'availableStocks'])->name('stocks.products.available');
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
        Route::get('/pharmacy_payment_accounts', [AdminController::class, 'pharmacy_payment_accounts'])->name('pharmacy_payment_accounts');
        Route::post('/pharmacy_payment_accounts', [AdminController::class, 'storePaymentAccount'])->name('pharmacy_payment_accounts.store');
        Route::put('/pharmacy_payment_accounts/{paymentAccount}', [AdminController::class, 'updatePaymentAccount'])->name('pharmacy_payment_accounts.update');
        Route::delete('/pharmacy_payment_accounts/{paymentAccount}', [AdminController::class, 'destroyPaymentAccount'])->name('pharmacy_payment_accounts.destroy');
        Route::get('/financials', [AdminController::class, 'financials'])->name('financials');
        // ── Financials (CRUD used by the Financial Records page) ─────────
        Route::get('/financials/data', [AdminFinancialsController::class, 'index'])->name('financials.data');
        Route::post('/financials', [AdminFinancialsController::class, 'store'])->name('financials.store');
        Route::put('/financials/{financial}', [AdminFinancialsController::class, 'update'])->name('financials.update');
        Route::delete('/financials/{financial}', [AdminFinancialsController::class, 'destroy'])->name('financials.destroy');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::post('/profile/photo', [AdminController::class, 'updatePhoto'])->name('profile.photo');
        Route::put('/profile/password', [AdminController::class, 'updatePassword'])->name('profile.password');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    });
});