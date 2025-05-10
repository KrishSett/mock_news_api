<?php

namespace App\Contracts\API;

/**
 * Interface CategoryContract
 *
 * @package App\Contracts\API\CategoryContract
 */
interface CategoryContract
{
    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function list(string $order = 'name', string $sort = 'asc', array $columns = ['*']): mixed;

    /**
     * @param int $id
     * @return mixed
     */
    public function findCategoryById(int $id): mixed;

    /**
     * @param string $slug
     * @return mixed
     */
    public function findCategoryBySlug(string $slug): mixed;

    /**
     * @param string $slug
     * @return mixed
     */
    public function fetchCategoryDetailsBySlug(string $slug): mixed;
}