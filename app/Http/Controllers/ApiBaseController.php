<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    public function __construct()
    {
    }

    /**
     * General success response (200).
     *
     * @param array $data
     * @param int|int $httpStatus
     */
    public function responseSuccess(array $data, int $httpStatus = 200)
    {
        return response()->json($data, $httpStatus);
    }

    /**
     * General error response (500).
     *
     * @param string|string $message
     * @param int $httpStatus
     */
    public function responseError(string $message = 'Error', $httpStatus = 500)
    {
        return response()->json(['error' => true, 'message'=> $message], $httpStatus);
    }

    /**
     * Validation error response (422).
     *
     * @param string|string $errorMessage
     */
    public function responseValidationError(array $errors = [])
    {
        if (empty($errors)) {
            $errors = [
                'Validation failed.'
            ];
        }

        return response()->json([
            'error' => true,
            'validation_errors' => $errors
        ], 422);
    }
}
