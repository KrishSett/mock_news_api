<?php


namespace App\Repositories\API;

use App\Models\API\News;
use App\Models\API\Tags;
use App\Repositories\BaseRepository;
use App\Contracts\API\TagContract;

class TagRepository extends BaseRepository implements TagContract
{
    protected $newsModel;

    /**
     * TagRepository constructor.
     *
     * @param Tags $model
     */
    public function __construct(Tags $model)
    {
        parent::__construct($model);
        $this->newsModel = new News();
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
     * Create a tag.
     *
     * @param array $attributes
     * @return mixed
     */
    public function createTag(array $attributes): mixed
    {
        try {
            $created = $this->create($attributes);
            return (bool) $created;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Fetch tag(s) related news
     * 
     * @param array $tags
     * @return array
     */
    public function tagNews(array $tags): array
    {
        $tagIds = $this->model->whereIn('slug', $tags)->pluck('id')->toArray();

        if (empty($tagIds)) {
            return [];
        }

        $news = $this->newsModel
            ->whereHas('tags', fn ($query) => $query->whereIn('tags_id', $tagIds)) // adjust if your pivot key is different
            ->select('uuid', 'title', 'thumbnail', 'short_description')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return $news->map(function ($item) {
            return [
                'uuid'              => $item->uuid,
                'title'             => $item->title,
                'thumbnail'         => $item->thumbnail,
                'short_description' => $item->short_description,
            ];
        })->toArray();
    }
}
