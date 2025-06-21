<?php

namespace App\Services\API;

use App\Contracts\API\GuestTokenContract;

class GuestTokenService
{
    /**
     * @var GuestTokenContract
     */
    protected $guestTokenRepository;

    /**
     * GuestTokenService constructor.
     *
     * @param GuestTokenContract $guestTokenRepository
     */
    public function __construct(GuestTokenContract $guestTokenRepository)
    {
        $this->guestTokenRepository = $guestTokenRepository;
    }

    /**
     * Create guest token for visitors.
     *
     * @param array $attr
     * @return bool
     */
    public function createToken(array $attr): bool
    {
        $params =  [
            'token'      => $attr[0],
            'visitor_id' => $attr[1],
            'ip_address' => getClientIp(),
            'expires_at' => \Carbon\Carbon::now()->modify("+ 1week")->format("Y-m-d H:i:s")
        ];

        return $this->guestTokenRepository->createToken($params);
    }

    /**
     * Fetch guest token for a visitor's id.
     *
     * @param string $visitorId
     * @return string|null
     */
    public function fetchGuestToken(string $visitorId): ?string
    {
        return $this->guestTokenRepository->fetchToken($visitorId);
    }
}
