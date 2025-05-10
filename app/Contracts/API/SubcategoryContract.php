<?php

namespace App\Contracts\API;

/**
 * Interface SubcategoryContract
 *
 * @package App\Contracts\API\SubcategoryContract
 */
interface SubcategoryContract
{
    /**
     * @param string $slug
     * @return mixed
     */
    public function fetchSubcategoryDetailsBySlug(string $slug): mixed;
}