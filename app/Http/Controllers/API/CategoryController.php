<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends ApiBaseController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
    }

    /**
     * List of all categories
     */
    public function fetchCategories()
    {
        $cached = Cache::get(config('apicachekeys.categories.list'));
        if (!empty($cached)) {
            return $cached;
        }
        
        $categories = $this->categoryService->fetchCategories([
            'order'   => 'list_order',
            'sort'    => 'asc',
            'columns' => ['id', 'name', 'slug', 'active']
        ]);
        
        if (!empty($categories)) {
            Cache::set(config('apicachekeys.categories.list'), $categories, 3600);
            return $this->responseSuccess($categories);
        }

        return $this->responseError("No Data Found.", 400);
    }

    /**
     * Get details of category with slug
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $slug
     */
    public function getCategory(Request $request, $slug)
    {
        $category = $this->categoryService->findCategoryBySlug($slug);
        if (!empty($category)) {
            return $this->responseSuccess($category);
        }

        return $this->responseError("No Category Found.", 400);
    }
}
