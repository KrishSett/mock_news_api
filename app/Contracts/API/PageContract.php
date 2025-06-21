<?php

namespace App\Contracts\API;

/**
 * Interface PageContract
 *
 * Defines the contract for static page operations
 */
interface PageContract
{
    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listPages(string $order = 'list_order', string $sort = 'asc', array $columns = ['*']): mixed;

    /**
     * @param string $slug
     * @return mixed
     */
    public function findPageBySlug(string $slug): mixed;

    /**
     * @param array $filters
     * @param array $columns
     * @return mixed
     */
    public function filteredPages(array $filters = [], array $columns = ['*']): mixed;
}
