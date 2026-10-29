<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

    Route::middleware('role:admin,editor')
        ->prefix('dashboard')
        ->controller(DashboardController::class)
        ->group(function () {
            Route::get('/', 'index')->name('dashboard');

            // Berita (Admin & Editor)
            Route::get('/berita', 'newsIndex')->name('dashboard.news.index');
            Route::get('/berita/tambah', 'newsCreate')->name('dashboard.news.create');
            Route::post('/berita', 'newsStore')->name('dashboard.news.store');
            Route::get('/berita/{news}/edit', 'newsEdit')->name('dashboard.news.edit');
            Route::put('/berita/{news}', 'newsUpdate')->name('dashboard.news.update');
            Route::delete('/berita/{news}', 'newsDestroy')->name('dashboard.news.destroy');

            // Kategori Berita (Admin & Editor)
            Route::get('/kategori-berita', 'categoriesIndex')->name('dashboard.categories.index');
            Route::post('/kategori-berita', 'categoriesStore')->name('dashboard.categories.store');
            Route::put('/kategori-berita/{category}', 'categoriesUpdate')->name('dashboard.categories.update');
            Route::delete('/kategori-berita/{category}', 'categoriesDestroy')->name('dashboard.categories.destroy');

            // Galeri (Admin & Editor)
            Route::get('/galeri', 'galleriesIndex')->name('dashboard.galleries.index');
            Route::get('/galeri/tambah', 'galleriesCreate')->name('dashboard.galleries.create');
            Route::post('/galeri', 'galleriesStore')->name('dashboard.galleries.store');
            Route::get('/galeri/{gallery}/edit', 'galleriesEdit')->name('dashboard.galleries.edit');
            Route::put('/galeri/{gallery}', 'galleriesUpdate')->name('dashboard.galleries.update');
            Route::delete('/galeri/{gallery}', 'galleriesDestroy')->name('dashboard.galleries.destroy');

            // Khusus Admin (Program, Jadwal, FAQ, Biaya Pendaftaran, Kelola Editor, Pengaturan Profil Admin)
            Route::middleware('role:admin')->group(function () {
                Route::get('/profil', 'profileEdit')->name('dashboard.profile.edit');
                Route::put('/profil', 'profileUpdate')->name('dashboard.profile.update');
                Route::put('/profil/password', 'passwordUpdate')->name('dashboard.profile.password');
                Route::get('/program', 'programsIndex')->name('dashboard.programs.index');
                Route::get('/program/tambah', 'programsCreate')->name('dashboard.programs.create');
                Route::post('/program', 'programsStore')->name('dashboard.programs.store');
                Route::get('/program/{program}/edit', 'programsEdit')->name('dashboard.programs.edit');
                Route::put('/program/{program}', 'programsUpdate')->name('dashboard.programs.update');
                Route::delete('/program/{program}', 'programsDestroy')->name('dashboard.programs.destroy');

                Route::get('/jadwal', 'schedulesIndex')->name('dashboard.schedules.index');
                Route::get('/jadwal/tambah', 'schedulesCreate')->name('dashboard.schedules.create');
                Route::post('/jadwal', 'schedulesStore')->name('dashboard.schedules.store');
                Route::get('/jadwal/{schedule}/edit', 'schedulesEdit')->name('dashboard.schedules.edit');
                Route::put('/jadwal/{schedule}', 'schedulesUpdate')->name('dashboard.schedules.update');
                Route::delete('/jadwal/{schedule}', 'schedulesDestroy')->name('dashboard.schedules.destroy');

                Route::get('/faq', 'faqsIndex')->name('dashboard.faqs.index');
                Route::get('/faq/tambah', 'faqsCreate')->name('dashboard.faqs.create');
                Route::post('/faq', 'faqsStore')->name('dashboard.faqs.store');
                Route::get('/faq/{faq}/edit', 'faqsEdit')->name('dashboard.faqs.edit');
                Route::put('/faq/{faq}', 'faqsUpdate')->name('dashboard.faqs.update');
                Route::delete('/faq/{faq}', 'faqsDestroy')->name('dashboard.faqs.destroy');

                Route::get('/biaya-pendaftaran', 'feesIndex')->name('dashboard.fees.index');
                Route::get('/biaya-pendaftaran/tambah', 'feesCreate')->name('dashboard.fees.create');
                Route::post('/biaya-pendaftaran', 'feesStore')->name('dashboard.fees.store');
                Route::get('/biaya-pendaftaran/{fee}/edit', 'feesEdit')->name('dashboard.fees.edit');
                Route::put('/biaya-pendaftaran/{fee}', 'feesUpdate')->name('dashboard.fees.update');
                Route::delete('/biaya-pendaftaran/{fee}', 'feesDestroy')->name('dashboard.fees.destroy');

                Route::get('/editor', 'editorsIndex')->name('dashboard.editors.index');
                Route::get('/editor/tambah', 'editorsCreate')->name('dashboard.editors.create');
                Route::post('/editor', 'editorsStore')->name('dashboard.editors.store');
                Route::get('/editor/{editor}/edit', 'editorsEdit')->name('dashboard.editors.edit');
                Route::put('/editor/{editor}', 'editorsUpdate')->name('dashboard.editors.update');
                Route::delete('/editor/{editor}', 'editorsDestroy')->name('dashboard.editors.destroy');
            });
        });
});
