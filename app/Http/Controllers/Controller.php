<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Success response
     * @param array $data
     * @param string $message
     * @param bool $error
     * @param int $httpStatus
     * @return void
     */
    abstract function responseSuccess(array $data, int $httpStatus = 200);

    /**
     * Error response
     * 
     * @param mixed $message
     * @param int $httpStatus
     * @return void
     */
    abstract function responseError(string $message = 'Error', $httpStatus = 500);
}
