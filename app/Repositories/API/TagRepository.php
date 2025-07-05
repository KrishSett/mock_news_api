<?php


namespace App\Repositories\API;

use App\Http\Resources\API\NewsResource;
use App\Models\API\News;
use App\Models\API\Tags;
use App\Repositories\BaseRepository;
use App\Contracts\API\TagContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * Fetch tag(s) related news.
     * 
     * @param array $tags
     * @param string|null $exceptNewsId
     * @return AnonymousResourceCollection
     */
    public function tagNews(array $tags, ?string $exceptNewsId = null): AnonymousResourceCollection
    {
        // Get tag IDs from tag slugs
        $tagIds = $this->model
            ->whereIn('slug', $tags)
            ->pluck('id')
            ->toArray();

        // Return empty resource collection if no tags matched
        if (empty($tagIds)) {
            return NewsResource::collection(collect());
        }

        // Fetch news matching tags
        $query = $this->newsModel
            ->whereHas('tags', function($query) use($tagIds){
                return $query->whereIn('tags_id', $tagIds);
            })
            ->distinct();
        
        if (!empty($query)) {
            $query = $query->whereNot('uuid', $exceptNewsId);
        }
        
        $news = $query->orderBy('created_at', 'desc')
            ->limit(config('homecontents.tagLimit', 5))
            ->get();

        return NewsResource::collection($news);
    }
}
