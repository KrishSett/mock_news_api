<?php

namespace App\Repositories\API;


use App\Contracts\API\NewsContract;
use App\Http\Resources\API\NewsResource;
use App\Repositories\BaseRepository;
use App\Models\API\News;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
            return null;
        }

        $news = $news->only(['title', 'description', 'short_description', 'thumbnail', 'subcategory', 'tags', 'created_at']);
        $news['created_at'] = \Carbon\Carbon::parse($news['created_at'])->format("F j, Y");
        return $news;
    }

    /**
     * Fetch latest news grouped by category.
     *
     * @return AnonymousResourceCollection>
     */
    public function latestNews(): array
    {
        // Get latest news joined with latest_contents table
        $latestNews = $this->model
            ->join('latest_contents', 'news.id', '=', 'latest_contents.news_id')
            ->where('latest_contents.active', true)
            ->orderBy('latest_contents.order')
            ->limit(config('homecontents.allowedContents', 5))
            ->get(['news.*']);

        $news = [
            'latest' => NewsResource::collection($latestNews),
        ];

        // Get active categories from config
        $activeCategories = array_filter(
            config('homecontents.categories', []),
            fn($item) => !empty($item['active'])
        );

        $latestNewsIds = $latestNews->pluck('id')->all();

        foreach (array_keys($activeCategories) as $category) {
            $news[$category] = $this->getHomePageCategoryNews($category, $latestNewsIds);
        }

        return $news;
    }

    /**
     * Get news items based on category, excluding latest.
     *
     * @param string $category
     * @param array $excludeIds
     * @return AnonymousResourceCollection
     */
    protected function getHomePageCategoryNews(string $category, array $excludeIds = []): AnonymousResourceCollection
    {
        $query = $this->model->whereHas('subcategory.category', function ($query) use ($category) {
                $query->where('slug', $category)
                    ->where('active', true);
            })
            ->latest('created_at')
            ->limit(config('homecontents.allowedContents', 5));

        if (!empty($excludeIds)) {
            $query->whereNotIn('id', $excludeIds);
        }

        return NewsResource::collection($query->get());
    }

    /**
     * Create a news
     * 
     * @param array $attributes
     * @return bool
     */
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
