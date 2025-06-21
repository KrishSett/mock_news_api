<?php

namespace App\Services\API;

use App\Contracts\API\CategoryContract;

class CategoryService
{
    /**
     * @var CategoryContract
     */
    protected $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param CategoryContract $categoryRepository
     */
    public function __construct(CategoryContract $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Fetch the full list of categories.
     *
     * @param array $params
     * @return mixed
     */
    public function fetchCategories(array $params = []): mixed
    {
        $order   = $params["order"] ?? "id";
        $sort    = $params["sort"] ?? "desc";
        $columns = $params["columns"] ?? ['*'];

        return $this->categoryRepository->list($order, $sort, $columns);
    }

    /**
     * Get category details with slug.
     *
     * @param string $slug
     * @return mixed
     */
    public function findCategoryBySlug(string $slug): mixed
    {
        $category = $this->categoryRepository->findCategoryBySlug($slug);
        if (empty($category)) {
            return [];
        }

        return [
            'status' => true,
            'name'   => $category->name,
            'href'   => '/news-category/' . $category->slug
        ];
    }
}
