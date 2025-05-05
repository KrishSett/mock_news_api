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


if (!function_exists('currentMillisecond')) {
    function currentMillisecond(): string
    {
        return round(microtime(true) * 1000);;
    }
}