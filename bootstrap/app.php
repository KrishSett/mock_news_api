<?php

use App\Exceptions\LoginMethodNotAllowedException;
use App\Http\Middleware\HashVerify;
use App\Http\Middleware\LogApiRequestResponse;
use function Illuminate\Log\log;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use  Illuminate\Http\Request as HttpRequest;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            
        ])->alias([
            'hashVerify' => HashVerify::class,
            'logApi' => LogApiRequestResponse::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (MethodNotAllowedHttpException $e, HttpRequest $request) {            
            if ($request->is('api/*')) {
                $err = $e->getMessage() ?? 'Unsupported HTTP Method';
                log()->channel('apilog')->error($err);
                throw new LoginMethodNotAllowedException();
            }
        });
    })->create();
