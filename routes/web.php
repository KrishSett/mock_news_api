<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ImageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/np-image/{filename}', [ImageController::class, 'showPrivateImage'])->where('filename', '.*');
