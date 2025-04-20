<?php

namespace App\Repositories\API;

use App\Contracts\API\CategoryContract;
use App\Repositories\BaseRepository;
use App\Models\API\Category;

class CategoryRepository extends BaseRepository implements CategoryContract
{
    /**
     * UserRepository constructor.
     *
     * @param Category $model
     */
    public function __construct(Category $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * Get list of all active categories
     * 
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function list(string $order = 'name', string $sort = 'asc', array $columns = ['*']): mixed
    {
        $data = [];
        $categories = $this->all($columns, $order, $sort)->filter(function ($item) {
            return $item->active === true;
        })->toArray();

        if (empty($categories)) {
            return [];
        }

        $data = array_combine(array_column($categories,'slug'), array_column($categories,'name'));
        return $data;
    }

    public function findCategoryById(int $id): mixed
    {
        $category = $this->model->find($id);
        return !empty($category) ? $category : [];
    }

    public function findCategoryBySlug(string $slug): mixed
    {
        $category = $this->findOneBy(['slug' => $slug]);

        return !empty($category) ? $category : [];
    }

    public function fetchCategoryDetailsBySlug(string $slug): mixed
    {
        $category = $this->model
            ->where(['slug'=> $slug, 'active' => true])
            ->with(['subcategories'])
            ->first();

        return !empty($category) ? $category : [];
    }
}
