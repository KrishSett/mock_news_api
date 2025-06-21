<?php
namespace App\Repositories\API;

use App\Contracts\API\HeaderHashContract;
use App\Repositories\BaseRepository;
use App\Models\API\HeaderHash;

class HeaderHashRepository extends BaseRepository implements HeaderHashContract
{
    /**
     * UserRepository constructor.
     *
     * @param HeaderHash $model
     */
    public function __construct(HeaderHash $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Fetch header hash token.
     *
     * @return array
     */
    public function list(): array
    {
        $data = [];

        $hashes = $this->all(['route_prefix', 'hash']);
        if ($hashes->isEmpty()) {
            return $data;
        }

        $hashes = $hashes->toArray();
        return array_combine(array_column($hashes, 'route_prefix'), array_column($hashes, 'hash'));
    }
}
