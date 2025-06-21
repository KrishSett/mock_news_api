<?php

use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\HomePageController;
use App\Http\Controllers\API\PageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SubcategoryController;


Route::post("/auth/login", [AuthController::class, "login"])->middleware(["logApi"])->name("login");

// Back-end APIs
Route::group(['prefix' => 'admin', 'middleware' => ['throttle:api', 'logApi'], 'name' => 'admin'], function () {
    Route::post('/create/news', [NewsController::class, 'createNews'])->name('create.news');
});

// Front-end APIS
Route::group(['middleware' => ['throttle:api', 'auth:sanctum', 'logApi'], 'name' => 'api'], function () {
    ## Guest Token
    Route::post('/guest/hash', [AuthController::class, 'guestHash'])->name('guest.hash');

    ## Feed Endpoints
    Route::middleware('hashVerify')->prefix('feed')->group(function () {
        Route::get('/home-page', [HomePageController::class, 'getContents'])->name('home.page');
        Route::get('/category-list', [CategoryController::class, 'fetchCategories'])->name('categories');
        Route::get('/category/{slug}', [CategoryController::class, 'getCategory'])->name('category.details')->where('slug', '[a-zA-Z0-9-]+');
        Route::get('/pages-list', [PageController::class, 'fetchPages'])->name('pages');
        Route::get('/page/{slug}', [PageController::class, 'getPage'])->name('page.details')->where('slug', '[a-zA-Z0-9-]+');
        Route::get('/pages/other', [PageController::class, 'otherPages'])->name('footer.links');
        Route::get('/subcategories/{slug}', [SubcategoryController::class, 'getSubcategory'])->name('category.details')->where('slug', '[a-zA-Z0-9-]+');
        Route::get('/news/details/{uuid}', [NewsController::class, 'getNews'])->name('news.details')->where('uuid', '[a-zA-Z0-9-]+');
        Route::post('/content/create', [ContentController::class, 'createContent'])->name('create.content');
    });

    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
});
