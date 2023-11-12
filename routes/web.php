<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
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

Route::controller(AuthenticationController::class)->group(function () {
    Route::get('login', 'showLoginPage')->middleware('guest')->name('login');

    Route::post('register', 'register')->middleware('guest');
    Route::post('login', 'login')->middleware('guest');
    Route::post('logout', 'logout')->middleware('auth')->name('logout');
});

Route::controller(VerifyEmailController::class)->name('verification.')->group(function () {
    Route::get('verify-email', 'notice')->name('notice');
    Route::get('verify-user/{token}', 'verify')->name('verify');
    Route::post('resend-email', 'resendEmail')->name('resend.email');
});

Route::controller(PasswordResetController::class)->name('password.')->group(function () {
    Route::post('forgot-password', 'mail')->name('forgot.mail');
    Route::get('reset-password/{token}', 'show')->name('reset.show');
    Route::post('reset-password', 'update')->name('reset.update');
});

Route::controller(MainController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/search', 'search')->name('search');
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy-policy');
    Route::get('/terms-of-use', 'termsOfUse')->name('terms-of-use');
});

Route::controller(ReportController::class)->group(function () {
    Route::post('/repots/store', 'store')->name('reports.store')->middleware('auth');
});

Route::controller(LikeController::class)->prefix('like')->name('like.')->middleware('auth')->group(function () {
    Route::post('/', 'store')->name('store');
});

Route::controller(FavoriteController::class)->prefix('favorite')->name('favorite.')->middleware('auth')->group(function () {
    Route::post('/', 'store')->name('store');

    Route::get('/quotes', 'quotes')->name('quotes');
    Route::get('/authors', 'authors')->name('authors');
});

Route::controller(UserController::class)->name('users.')->group(function () {
    Route::get('/users/{slug}', 'show')->name('show');

    Route::get('/profile', 'profile')->name('profile')->middleware('auth');
    Route::get('/create-quote', 'createQuote')->name('quotes.create')->middleware('auth');
    Route::get('/users/{slug}/quotes', 'quotes')->name('quotes'); // anyone can see any users quotes
    Route::get('/edit-quotes', 'currentUsersQuotes')->name('current.quotes')->middleware('auth'); // show current users list of quotes for edit
    Route::get('/unverified-quotes', 'unverifiedQuotes')->name('quotes.unverified')->middleware('auth'); // show current users list of unverified quotes
    Route::get('/edit-quotes/{id}', 'editQuote')->name('quotes.edit')->middleware('auth'); // show edit form for the specific current users quote

    Route::post('/update', 'update')->name('update')->middleware('auth');
    Route::post('/users/quotes/store', 'storeQuote')->name('quotes.store')->middleware('auth');
    Route::post('/users/quotes/update', 'updateQuote')->name('quotes.update')->middleware('auth');
});

Route::controller(QuoteController::class)->prefix('quotes')->name('quotes.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/top', 'top')->name('top');

    Route::post('/ajax-get', 'ajaxGet')->name('ajax.get');
});

Route::controller(AuthorController::class)->prefix('authors')->name('authors.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{slug}', 'show')->name('show');

    Route::post('/ajax-get', 'ajaxGet')->name('ajax.get');
});

Route::middleware(['auth', 'admin'])->prefix('dashboard')->group(function () {
    Route::get('/', [QuoteController::class, 'dashboardIndex'])->name('dashboard.index');
    Route::post('/get-unverified-quotes-count', [QuoteController::class, 'getUnverifiedQuotesCount']);

    Route::controller(QuoteController::class)->prefix('/quotes')->name('quotes.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::get('/unapproved', 'dashboardUnapproved')->name('dashboard.unapproved.index');
        Route::get('/unapproved/{id}', 'editUnapproved')->name('dashboard.unapproved.edit');
        Route::get('/{id}', 'edit')->name('edit');

        Route::post('/store', 'store')->name('store');
        Route::post('/update', 'update')->name('update');
        Route::post('/destroy', 'destroy')->name('destroy');
        Route::post('/approve', 'approve')->name('approve');
    });

    Route::controller(SourceController::class)->prefix('/sources')->name('sources.')->group(function () {
        Route::get('/', 'dashboardIndex')->name('dashboard.index');
    });

    Route::controller(AuthorController::class)->prefix('/authors')->name('authors.')->group(function () {
        Route::get('/', 'dashboardIndex')->name('dashboard.index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}', 'edit')->name('edit');

        Route::post('/store', 'store')->name('store');
        Route::post('/update', 'update')->name('update');
        Route::post('/destroy', 'destroy')->name('destroy');
    });

    Route::controller(CategoryController::class)->prefix('/categories')->name('categories.')->group(function () {
        Route::get('/', 'dashboardIndex')->name('dashboard.index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}', 'edit')->name('edit');

        Route::post('/store', 'store')->name('store');
        Route::post('/update', 'update')->name('update');
        Route::post('/destroy', 'destroy')->name('destroy');
    });

    Route::controller(OptionController::class)->prefix('/options')->name('options.')->group(function () {
        Route::get('/', 'dashboardIndex')->name('dashboard.index');
        Route::get('/{id}', 'edit')->name('edit');

        Route::post('/update', 'update')->name('update');
    });

    Route::controller(UserController::class)->prefix('/users')->name('users.')->group(function () {
        Route::get('/', 'dashboardIndex')->name('dashboard.index');
    });

    Route::controller(ReportController::class)->prefix('/reports')->name('reports.')->group(function () {
        Route::get('/', 'dashboardIndex')->name('dashboard.index');
        Route::get('/{id}', 'edit')->name('edit');

        Route::post('/destroy', 'destroy')->name('destroy');
    });
});
