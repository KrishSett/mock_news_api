<?php

namespace App\Repositories\API;

use App\Contracts\API\PageContract;
use App\Repositories\BaseRepository;
use App\Models\API\Page;

class PageRepository extends BaseRepository implements PageContract
{
    protected const CHUNK_SIZE = 4;

    /**
     * PageRepository constructor.
     *
     * @param Page $model
     */
    public function __construct(Page $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Get list of all active pages.
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listPages(string $order = 'list_order', string $sort = 'asc', array $columns = ['*']): mixed
    {
        $pages = $this->model
            ->where('active', true)
            ->where('footer_link', true)
            ->orderBy($order, $sort)
            ->select($columns)
            ->get()
            ->map(function ($page) {
                return [
                    'slug' => $page->slug,
                    'title' => $page->name,
                    'url' => '/page/' . $page->slug
                ];
            })
            ->toArray();

        if (empty($pages)) {
            return [];
        }

        return array_chunk($pages, self::CHUNK_SIZE) ?: [];
    }

    /**
     * Get full page details by slug.
     *
     * @param string $slug
     * @return mixed
     */
    public function findPageBySlug(string $slug): mixed
    {
        $page = $this->model
            ->where('slug', $slug)
            ->where('active', true)
            ->select(['slug', 'name', 'description'])
            ->first();

        if (empty($page)) {
            return [];
        }

        return [
            'title'       => $page->name,
            'description' => $page->description
        ];
    }

    /**
     * Get filtered pages with custom conditions.
     *
     * @param array $columns
     * @param array $filters
     * @return mixed
     */
    public function filteredPages(array $columns = ['*'], array $filters = [], ): mixed
    {
        $query = $this->model->where('active', true);
        foreach ($filters as $key => $value) {
            $query->where($key, $value);
        }

        $pages = $query
            ->select($columns)
            ->get()
            ->map(function ($page) {
                return [
                    'title' => $page->name,
                    'href'  => '/pages/' . $page->slug
                ];
            })
            ->toArray();

        if (empty($pages)) {
            return [];
        }

        return array_chunk($pages, self::CHUNK_SIZE) ?: [];
    }
}
