<?php

namespace App\Contracts\API;

/**
 * Interface GuestTokenContract
 *
 * @package App\Contracts\API\GuestTokenContract
 */
interface GuestTokenContract
{
    /**
     * @param array $attr
     * @return bool
     */
    public function createToken(array $attr): bool;

    /**
     * @param string $visitorId
     * @return null|string
     */
    public function fetchToken(string $visitorId): ?string;
}