<?php

use App\Http\Controllers\API\ContentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SubcategoryController;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');


Route::post("/auth/login", [AuthController::class, "login"])->name("login");

Route::group(["middleware" => ["throttle:api", "auth:sanctum", "logApi"], "name" => "api"], function () {

    Route::post("/hashes/get", [AuthController::class, "getHashes"])->name("hash.details");

    Route::post("/content/create", [ContentController::class, "createContent"])->name("create.content");

    ## Feed Endpoints
    Route::middleware('hashVerify')->prefix('feed')->group(function () {
        Route::get('/categories', [CategoryController::class, 'fetchCategories'])->name('categories');
        Route::get('/categories/{slug}', [CategoryController::class, 'getCategory'])->name('category.details')->where('slug', '[a-zA-Z0-9-]+');
        Route::get('/subcategories/{slug}', [SubcategoryController::class, 'getSubcategory'])->name('category.details')->where('slug', '[a-zA-Z0-9-]+');
        Route::get('/news/details/{uuid}', [NewsController::class, 'getNews'])->name('news.details')->where('uuid', '[a-zA-Z0-9-]+');
    });

    Route::post("/auth/logout", [AuthController::class, "logout"])->name("logout");
});