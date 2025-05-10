<?php


use Illuminate\Support\Facades\Log;

if (!function_exists('decryptAuthHash')) {
    function decryptAuthHash(string $base64Payload): ?array
    {
        $combinedString = base64_decode($base64Payload);

        if (!$combinedString) {
            return null;
        }

        $parts = explode(';', $combinedString, 2);
        if (empty($parts) || count($parts) !== 2) {
            return null;
        }

        list($encodedJsonPayload, $secretKey) = $parts;
        if (trim($secretKey) !== env('ENCRYPTION_KEY')) {
            return null;
        }

        $jsonPayload = base64_decode($encodedJsonPayload);
        if (!$jsonPayload) {
            return null;
        }

        $payload = json_decode($jsonPayload, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($payload)) {
            return null;
        }

        if (!isset($payload['timestamp']) || abs(currentMillisecond() - $payload['timestamp']) > 25000) {
            return null;
        }

        return $payload;
    }
}

if (!function_exists('encryptAuthToken')) {
    function encryptAuthToken(array $data): string
    {
        $jsonData = json_encode($data);
        return base64_encode(base64_encode($jsonData) .";". env('ENCRYPTION_KEY'));
    }
}

if (!function_exists('currentMillisecond')) {
    function currentMillisecond(): string
    {
        return round(microtime(true) * 1000);
    }
}

if (!function_exists('timestampInMilliseconds')) {
    function timestampInMilliseconds(?string $modifier = null): string
    {
        $time = microtime(true);
        
        if ($modifier !== null) {
            $time = strtotime($modifier, $time);
        }
        
        return (string) round($time * 1000);
    }
}

if (!function_exists('getClientIp')) {
    function getClientIp(): string
    {
        $ipSources = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];

        foreach ($ipSources as $source) {
            if (!empty($_SERVER[$source])) {
                return $_SERVER[$source];
            }
        }

        return null;
    }
}