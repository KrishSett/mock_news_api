<?php

namespace App\Http\Middleware;

use App\Events\ApiRequestStarted;
use App\Events\ApiResponseFinished;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\JsonResponse;

class LogApiRequestResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\JsonResponse)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        event(new ApiRequestStarted($request));
        $response = $next($request);
        event(new ApiResponseFinished($request, $response));

        return $response;
    }
}
