<?php

namespace App\Http\Middleware;

use App\Models\API\GuestToken;
use App\Models\API\HeaderHash;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use function Illuminate\Log\log;

class HashVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hash = $request->header("x-auth-type");
        $visitor = $request->header("x-user-id");

        if (empty($hash) || empty($visitor)) {
            return response()->json([
                'error' => true,
                'message' => 'Access Prohibited.'
            ], Response::HTTP_FORBIDDEN);
        }

        $tokenModel = new GuestToken();
        $verified = $tokenModel->checkHash($visitor, $hash);

        if (!$verified) {
            return response()->json([
                'error' => true,
                'message' => 'Access Prohibited.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
