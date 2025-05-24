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
    public function list(string $order = 'list_order', string $sort = 'asc', array $columns = ['*']): mixed
    {
        $data = [];
        $categories = $this->model->with(['subcategories'])
            ->where('active', true)
            ->orderBy($order, $sort)
            ->select($columns)
            ->get();

        if ($categories->isEmpty()) {
            return $data;
        }

        foreach ($categories as $category) {
            $tmp                 = [];
            $key                 = $category->slug;
            $categoryName        = $category->name ?? '';
            $data[$key]['title'] = $categoryName;
            
            if ($category->subcategories->isNotEmpty()) {
                foreach ($category->subcategories as $subcategory) {
                    $tmp[] = [
                        'title' => $subcategory->name,
                        'slug'  => $subcategory->slug,
                        'href'  => '/news-category/' . $subcategory->slug,
                    ];
                }

                $data[$key]['subcategories'] = $tmp;
            } else {
                $data[$key]['href'] = '/news-category/' . $key;
            }
        }

        if (!empty($data)) {
            $data = array_merge(['home' => [
                'title' => 'Home',
                'href' => '/'
            ]], $data);
        }

        return $data;
    }

    /**
     * Get category details with id
     * 
     * @param int $id
     * @return mixed
     */
    public function findCategoryById(int $id): mixed
    {
        $category = $this->model->find($id);
        return !empty($category) ? $category : [];
    }

    /**
     * Get summary of category by slug
     * 
     * @param string $slug
     * @return mixed
     */
    public function findCategoryBySlug(string $slug): mixed
    {
        $category = $this->findOneBy(['slug' => $slug])?->activeCategories();
        return !empty($category) ? $category : [];
    }
}
