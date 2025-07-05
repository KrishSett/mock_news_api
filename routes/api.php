<?php

use App\Http\Controllers\API\ContentController;
use App\Http\Controllers\API\HomePageController;
use App\Http\Controllers\API\LatestContentController;
use App\Http\Controllers\API\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SubcategoryController;
use App\Http\Controllers\API\TagController;

Route::post("/auth/login", [AuthController::class, "login"])->middleware(["logApi"])->name("login");

// Back-end (admin) APIs
Route::group(['prefix' => 'admin', 'middleware' => ['throttle:api', 'logApi'], 'name' => 'admin'], function () {
    Route::get('/list/tags', [TagController::class, 'listTags'])->name('list.tags');
    Route::post('/create/tag', [TagController::class, 'createTag'])->name('create.tag');
    Route::post('/create/news', [NewsController::class, 'createNews'])->name('create.news');
    Route::post('/create/latest-content', [LatestContentController::class, 'createLatestContent'])->name('create.latest.content');
});

// Front-end APIS
Route::group(['middleware' => ['throttle:api', 'auth:sanctum', 'logApi'], 'name' => 'api'], function () {
    // Guest Token
    Route::post('/guest/hash', [AuthController::class, 'guestHash'])->name('guest.hash');

    // Feed Endpoints
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
        Route::post('/tag/news', [TagController::class, 'tagNews'])->name('tag.news');
    });

    // Logout
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
});
