<?php

namespace App\Repositories\API;


use App\Contracts\API\NewsContract;
use App\Repositories\BaseRepository;
use App\Models\API\News;

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
}