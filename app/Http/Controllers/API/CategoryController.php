<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends ApiBaseController
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        parent::__construct();
        $this->categoryService = $categoryService;
    }

    public function fetchCategories()
    {
        $categories = $this->categoryService->fetchList([
            'order' => 'name',
            'sort' => 'asc', 
            'columns' => [
                'name',
                'slug',
                'active'
            ]
        ]);
        
        if (!empty($categories)) {
            return $this->responseSuccess($categories);
        }

        return $this->responseError("No Data Found.", 400);
    }

    public function getCategory(Request $request, $slug)
    {
        $category = $this->categoryService->fetchCategoryDetailsBySlug($slug);

        if (!empty($category)) {
            return $this->responseSuccess($category);
        }

        return $this->responseError("No Category Found.", 400);
    }
}
