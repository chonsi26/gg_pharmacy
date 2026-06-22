<?php

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