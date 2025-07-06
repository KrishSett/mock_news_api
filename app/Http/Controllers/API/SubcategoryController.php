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
            return $this->responseValidationError($validator->errors()->all());
        }

        return $this->subcategoryService->fetchSubcategoryDetailsBySlug($slug);
    }
}
