<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class LoginMethodNotAllowedException extends Exception
{
    protected $message = 'The requested method is not supported for this endpoint.';
    protected $code;

    public function __construct(string $message = null, $code = 405)
    {
        if (!empty($message)) {
            $this->message = $message;
        }

        $this->code = $code;
    }

    public function render($request)
    {
        return response()->json(['message' => $this->message], $this->code);
    }
}
