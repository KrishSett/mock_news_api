<?php

namespace App\Services\API;

use App\Contracts\API\HeaderHashContract;

class HeaderHashService
{
    /**
     * @var HeaderHashContract
     */
    protected $headerHashRepository;

    /**
     * HeaderHashService constructor.
     *
     * @param HeaderHashContract $headerHashRepository
     */
    public function __construct(HeaderHashContract $headerHashRepository)
    {
        $this->headerHashRepository = $headerHashRepository;
    }

    /**
     * Fetch the full list of categories.
     *
     * @return array
     */
    public function fetchList(): array
    {
        $hashes = $this->headerHashRepository->list();

        if (empty($hashes)) {
            return [];
        }

        return array_map(function ($item) {
            return $this->getHashKey($item);
        }, $hashes);
    }

    /**
     * Generate hash key from header hash.
     *
     * @param string $headerHash
     * @return string|null
     */
    protected function getHashKey(string $headerHash): ?string
    {
        if ($headerHash === null || $headerHash === '') {
            return null;
        }

        $hashAttr = [
            $headerHash,
            sha1(getenv('HASH_KEY_SALT') . time()),
            \Carbon\Carbon::now('UTC')->addDay()->timestamp,
            uniqid('NP', false),
        ];

        return base64_encode(implode('|', $hashAttr));
    }
}
