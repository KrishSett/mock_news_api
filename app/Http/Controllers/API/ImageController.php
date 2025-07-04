<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;

class ImageController extends ApiBaseController
{
    /**
     * Display news images
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function showPrivateImage(Request $request): mixed
    {
        $filename = $request?->filename ?? '';
        $path = storage_path('app/private/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filename));

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
