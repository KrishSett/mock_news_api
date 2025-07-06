<?php

namespace App\Repositories\API;

use App\Contracts\API\SubcategoryContract;
use App\Http\Resources\API\NewsResource;
use App\Repositories\BaseRepository;
use App\Models\API\Subcategory;

class SubcategoryRepository extends BaseRepository implements SubcategoryContract
{
    /**
     * UserRepository constructor.
     *
     * @param Subcategory $model
     */
    public function __construct(Subcategory $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Fetch subcategory details with slug
     *
     * @param string $slug
     * @return mixed
     */
    public function fetchSubCategoryDetailsBySlug(string $slug): mixed
    {
        $subcategory = $this->model
            ->where(['slug'=> $slug, 'active' => true])
            ->with(['news', 'news.tags'])
            ->first();

        if (empty($subcategory)) {
            return [];
        }

        $news = $subcategory->news()->orderBy('created_at','desc');
        return NewsResource::collection($news->paginate(config('news.news_paginate')));
    }
}
