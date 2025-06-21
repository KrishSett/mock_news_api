<?php
namespace App\Repositories\API;

use App\Contracts\API\GuestTokenContract;
use App\Repositories\BaseRepository;
use App\Models\API\GuestToken;

class GuestTokenRepository extends BaseRepository implements GuestTokenContract
{
    /**
     * UserRepository constructor.
     *
     * @param GuestToken $model
     */
    public function __construct(GuestToken $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Crate guest token for visitors.
     *
     * @param array $attr
     * @return bool
     */
    public function createToken(array $attr): bool
    {
        $created = $this->create($attr);
        return $created ? true : false;
    }

    /**
     * Fetch visitor token with it's id.
     *
     * @param string $visitorId
     * @return string|null
     */
    public function fetchToken(string $visitorId): ?string
    {
        $token = $this->findOneBy(['visitor_id' => $visitorId]);
        if (!$token || $token->expires_at <= \Carbon\Carbon::now()) {
            return null;
        }

        return $token->token;
    }
}
