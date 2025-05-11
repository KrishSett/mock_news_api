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
     * List all active static pages with optional sorting
     *
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listPages(string $order = 'list_order', string $sort = 'asc', array $columns = ['*']): mixed;

    /**
     * Get complete details of a page by its slug
     *
     * @param string $slug
     * @return mixed
     */
    public function findPageBySlug(string $slug): mixed;

    /**
     * Get filtered pages (e.g., footer-only pages)
     *
     * @param array $filters
     * @param array $columns
     * @return mixed
     */
    public function filteredPages(array $filters = [], array $columns = ['*']): mixed;
}