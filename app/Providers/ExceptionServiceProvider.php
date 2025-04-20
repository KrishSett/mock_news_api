<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Exceptions\LoginMethodNotAllowedException;

class ExceptionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        
        // $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
        //     if ($request->is('api/auth/login') && $request->isMethod('get')) {
        //         throw new LoginMethodNotAllowedException();
        //     }
        // });
    }
}
