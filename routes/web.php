<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    App\Http\Controllers\ArticleController::class, 'index'
])->name('root');

Route::resource('articles', App\Http\Controllers\ArticleController::class)
    ->middleware(['auth'])
    ->only(['create', 'store', 'edit', 'update', 'destroy']);
//  ->except(['index', 'show']);  // こちらでも可

Route::resource('articles', App\Http\Controllers\ArticleController::class)
    ->only(['index', 'show']);
//  ->except(['create', 'store', 'edit', 'update', 'destroy']);  // こちらでも可

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

// authから始まるルーティングに認証前にアクセスがあった場合
Route::prefix('auth')->middleware('guest')->group(function () {
    // auth/githubにアクセスがあった場合はOAuthControllerのredirectToProviderアクションへルーティング
    Route::get('/{provider}', [
        App\Http\Controllers\Auth\OAuthController::class, 'redirectToProvider'
    ])->where('provider', 'github|google')->name('redirectToProvider');

    // auth/github/callbackにアクセスがあった場合はOAuthControllerのoauthCallbackアクションへルーティング
    Route::get('/{provider}/callback', [
        App\Http\Controllers\Auth\OAuthController::class, 'oauthCallback'
    ])->where('provider', 'github|google')->name('oauthCallback');
});
