<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\SubcategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends ApiBaseController
{
    /**
     * @var SubcategoryService
     */
    protected $subcategoryService;

    /**
     * SubcategoryController constructor.
     *
     * @param SubcategoryService $subcategoryService
     */
    public function __construct(SubcategoryService $subcategoryService)
    {
        parent::__construct();
        $this->subcategoryService = $subcategoryService;
    }

    /**
     * Get subcategory.
     *
     * @param Request $request
     * @param $slug
     */
    public function getSubcategory(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
            "page" => ["nullable", "integer", "min:1"],
        ]);

        if ($validator->fails()) {
            return $this->responseValidationError($validator->errors()->all()); // Use 422 for validation errors
        }

        $page = 1;
        if ($request->has('page')) {
            $page = (int) $request->page;
        }

        $subcategory = $this->subcategoryService->fetchSubcategoryDetailsBySlug($slug);

        if (!empty($subcategory)) {
            return $this->responseSuccess($subcategory);
        }

        return $this->responseError("No Subcategory Found.", 400);
    }
}
