<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ImageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/private-image/{filename}', [ImageController::class, 'showPrivateImage'])->where('filename', '.*');
