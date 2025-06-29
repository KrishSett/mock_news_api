<?php

namespace App\Repositories\API;

use App\Models\API\LatestContent;
use App\Repositories\BaseRepository;
use App\Contracts\API\LatestContentContract;

class LatestContentRepository extends BaseRepository implements LatestContentContract
{
    /**
     * LatestContentRepository constructor.
     *
     * @param LatestContent $model
     */
    public function __construct(LatestContent $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Create latest content.
     *
     * @param array $attr
     * @return bool
     */
    public function createLatestContent(array $attr): mixed
    {
        try {
            $created = $this->create($attr);
            return (bool) $created;
        } catch (\PDOException $e) {
            return false;
        }
    }
}
