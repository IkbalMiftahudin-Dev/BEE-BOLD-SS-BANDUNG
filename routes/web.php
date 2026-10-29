<?php

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
