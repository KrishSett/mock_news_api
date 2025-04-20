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
     * Instance of the class
     * 
     * @param \App\Contracts\API\CategoryContract $categoryRepository
     */
    public function __construct(CategoryContract $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Fetch the full list of categories
     * 
     * @param array $params
     * @return mixed
     */
    public function fetchList(array $params = []): mixed
    {
        $order = $params["order"] ?? "id";
        $sort = $params["sort"] ?? "desc";
        $columns = $params["columns"] ?? ['*'];

        return $this->categoryRepository->list($order, $sort, $columns);
    }

    public function findCategoryBySlug(string $slug): mixed
    {
        return $this->categoryRepository->findCategoryBySlug($slug);
    }

    public function fetchCategoryDetailsBySlug(string $slug): mixed
    {
        $category = $this->categoryRepository->fetchCategoryDetailsBySlug($slug);

        if (empty($category)) {
            return [];
        }

        $details = [];
        $subcategories = $category->subcategories()
            ->where(['active' => true])
            ->get();

        foreach ($subcategories as $subcategory) {
            $tmp = [
                'slug' => $subcategory->slug,
                'name' => $subcategory->name,
                'description' => $subcategory->description
            ];

            $details[] = $tmp;
        }

        return [
            'slug' => $slug,
            'name'=> $category->name,
            'subcategories' => $details
        ];
    }
}
