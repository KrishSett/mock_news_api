<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    public function __construct()
    {

    }

    public function responseSuccess(array $data, int $httpStatus = 200)   
    {
        return response()->json($data, $httpStatus);
    }

    public function responseError(string $message = 'Error', $httpStatus = 500)
    {
        return response()->json(['error' => true, 'message'=> $message], $httpStatus);
    }
}
