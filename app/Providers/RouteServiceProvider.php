<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
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
        RateLimiter::for('api', function (Request $request) {
            $userId = $request->hasHeader('X-User-Id') ? $request->header('X-User-Id') : $request->user()?->id;
            return Limit::perMinute(1000)->by($userId)->response(function (Request $request, array $headers) {
                return response()->json(['error' => true, 'message' => 'Too many Requests'], 429);
            });
        });
    }
}
