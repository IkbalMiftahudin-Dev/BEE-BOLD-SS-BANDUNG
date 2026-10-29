<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicPageController;
use Illuminate\Support\Facades\Route;

Route::controller(PublicPageController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/berita', 'allNews')->name('berita.index');
    Route::get('/berita/{slug}', 'showNews')
        ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
        ->name('berita.show');
    Route::get('/galeri', 'allGalleries')->name('galeri.index');

    // Throttled to limit automated abuse of the public registration endpoint.
    Route::post('/daftar/whatsapp', 'redirectWhatsAppRegistration')
        ->middleware('throttle:10,1')
        ->name('registration.whatsapp');
});

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')
        ->middleware('throttle:10,1')
        ->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('role:admin,editor')->group(function () {
        Route::get('/dashboard', fn () => view('dashboard.index'))->name('dashboard');
    });
});
