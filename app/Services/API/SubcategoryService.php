<?php

namespace App\Services\API;

use App\Contracts\API\SubcategoryContract;

class SubcategoryService
{
    /**
     * @var SubcategoryContract
     */
    protected $subCategoryRepository;

    /**
     * SubcategoryService constructor.
     *
     * @param SubcategoryContract $subCategoryRepository
     */
    public function __construct(SubcategoryContract $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    /**
     * fetch subcategory details with slug.
     *
     * @param string $slug
     * @return mixed
     */
    public function fetchSubcategoryDetailsBySlug(string $slug): mixed
    {
        return $this->subCategoryRepository->fetchSubcategoryDetailsBySlug($slug);
    }
}
