<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage', [
        'news' => require resource_path('data/news.php'),
    ]);
})->name('home');

Route::get('/berita', function () {
    return view('berita', [
        'news' => require resource_path('data/news.php'),
    ]);
})->name('berita.index');

Route::get('/berita/{slug}', function (string $slug) {
    $news = collect(require resource_path('data/news.php'));
    $article = $news->firstWhere('slug', $slug);
    abort_unless($article, 404);

    return view('berita-detail', [
        'article' => $article,
        'related' => $news->where('slug', '!=', $slug)->values(),
        'total' => $news->count(),
    ]);
})->name('berita.show');

Route::get('/galeri', function () {
    return view('galeri');
})->name('galeri.index');
