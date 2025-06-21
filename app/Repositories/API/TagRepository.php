<?php


namespace App\Repositories\API;

use App\Models\API\Tags;
use App\Repositories\BaseRepository;
use App\Contracts\API\TagContract;

class TagRepository extends BaseRepository implements TagContract
{
    /**
     * TagRepository constructor.
     *
     * @param Tags $model
     */
    public function __construct(Tags $model)
    {
        parent::__construct($model);
    }

    /**
     *List of all tags based on filter.
     *
     * @param array $filter
     * @return mixed
     */
    public function listTags(array $filter): mixed
    {
        $active = $filter['type'] !== 'all' ? ['active' => true] : [];
        $tags = $this->findBy($active);

        if ($tags->isNotEmpty()) {
            return $tags->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name
                ];
            });
        }

        return [];
    }

    /**
     * @inheritDoc
     */
    public function createTag(array $attributes): mixed
    {
        // TODO: Implement createTag() method.
    }
}
