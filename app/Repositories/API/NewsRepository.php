<?php

namespace App\Repositories\API;


use App\Contracts\API\NewsContract;
use App\Repositories\BaseRepository;
use App\Models\API\News;
use Illuminate\Support\Str;

class NewsRepository extends BaseRepository implements NewsContract
{
    /**
     * UserRepository constructor.
     *
     * @param News $model
     */
    public function __construct(News $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Get news details with uuid.
     *
     * @param string $uuid
     * @return mixed
     */
    public function getNews(string $uuid): mixed
    {
        $news = $this->model->where("uuid", $uuid)
            ->with([
                "subcategory" => function ($query) {
                    $query->select("subcategories.id", "subcategories.slug");
                },
                "tags" => function ($query) {
                    $query->select("tags.id", "tags.slug");
                }
            ])
            ->first();

        if (!$news) {
            return null; // Or you could return an appropriate error response
        }

        return $news->only(['title', 'description', 'thumbnail', 'subcategory', 'tags']);
    }

    /**
     * Fetch latest newses.
     *
     * @return array
     */
    public function latestNews(): array
    {
        // Get latest news with their IDs
        $latest = $this->model->join('latest_contents', 'news.id', '=', 'latest_contents.news_id')
            ->where('latest_contents.active', true)
            ->select('news.id', 'news.uuid', 'news.title', 'news.thumbnail', 'news.short_description')
            ->orderBy('latest_contents.order')
            ->limit(config('homecontents.allowedContents', 5)) // Default to 5 if not set
            ->get();

        $newses = [
            'latest' => $latest->toArray()
        ];

        // Get active categories from config
        $activeCategories = array_filter(
            config('homecontents.categories', []),
            function ($item) {
                return isset($item['active']) ? $item['active'] : false;
            }
        );

        // Only query for categories if we have latest news to exclude
        $latestNewsIds = $latest->pluck('id')->all();

        foreach (array_keys($activeCategories) as $category) {
            $newses[$category] = $this->getHomePageCategoryNews($category, $latestNewsIds);
        }

        return $newses;
    }

    /**
     * Get newses based on categories.
     *
     * @param string $category
     * @param array $excludeIds
     * @return array
     */
    protected function getHomePageCategoryNews(string $category, array $excludeIds = []): array
    {
        $query = $this->model->whereHas('subcategory.category', function ($query) use ($category) {
                $query->where('slug', $category)
                    ->where('active', 1);
            })
            ->select('id', 'uuid', 'title', 'thumbnail', 'short_description')
            ->latest('created_at')
            ->limit(config('homecontents.allowedContents', 5));

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return $query->get()->toArray();
    }

    public function createNews(array $attributes): mixed
    {
        $tagIds = array_column($attributes['tags'], 'id');
        unset($attributes['tags']);

        // Create flat associative array for sync
        $tagsWithTimestamps = [];
        $currentTime = now()->timezone('utc')->format('Y-m-d H:i:s');

        foreach ($tagIds as $id) {
            $tagsWithTimestamps[$id] = [
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ];
        }

        // Generate UUID
        $attributes['uuid'] = Str::uuid()->toString();

        $news = $this->create($attributes);

        if ($news) {
            $news->tags()->sync($tagsWithTimestamps);
            return true;
        }

        return false;
    }

}
