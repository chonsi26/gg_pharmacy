<?php

use App\Http\Controllers\AdminController;
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

    // Auth-only: dashboard
    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    });
});