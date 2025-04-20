<?php

namespace App\Http\Middleware;

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
        $authHash = $request->header("x-auth-type"); 

        if (empty($authHash)) {
            return response()->json([
                'error' => true,
                'message' => 'Access Prohibited.'
            ], Response::HTTP_FORBIDDEN);
        }

        $hash = $this->processEncodedString($authHash);

        if (empty($hash)) {
            return response()->json([
                'error' => true,
                'message' => 'Invalid or expired token.'
            ], Response::HTTP_FORBIDDEN);
        }

        $urlSegments = $request->segments() ?? [];
        $prefix = $urlSegments[2] ?? null;

        $hashModel = new HeaderHash();
        $verified = $hashModel->checkHash($prefix, $hash);

        if (!$verified) {
            return response()->json([
                'error' => true,
                'message' => 'Access Prohibited.'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    public function processEncodedString(string $base64Encoded): ?string
    {
        // Attempt to decode base64 string
        $decodedString = base64_decode($base64Encoded);

        if ($decodedString === false) {
            log()->channel('apilog')->error('Base64 decoding failed.', ['input' => $base64Encoded]);
            return null;
        }

        // Split the string into expected parts
        $parts = explode('|', $decodedString);
        if (count($parts) !== 4) {
            log()->channel('apilog')->error('Decoded string does not contain exactly 4 parts.', [
                'decoded_string' => $decodedString,
                'parts_count' => count($parts)
            ]);
            return null;
        }

        list($hashFromDb, $salt, $expiryTimestamp, $randomPart) = $parts;

        // Validate expiry timestamp
        if (!is_numeric($expiryTimestamp)) {
            log()->channel('apilog')->error('Expiry timestamp is not numeric.', ['expiry_timestamp' => $expiryTimestamp]);
            return null;
        }

        $expiry = (int) $expiryTimestamp;
        $currentTime = \Carbon\Carbon::now('UTC')->timestamp;

        // Check if the token is expired
        if ($currentTime >= $expiry) {
            log()->channel('apilog')->error('Encoded string has expired.', [
                'expiry' => $expiry,
                'current_time' => $currentTime
            ]);
            return null;
        }

        // Successfully processed
        log()->channel('apilog')->info('Encoded string processed successfully.', [
            'hash' => $hashFromDb,
            'expiry' => $expiry
        ]);

        return $hashFromDb;
    }

}
