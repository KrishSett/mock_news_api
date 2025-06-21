<?php

namespace App\Services\API;

use App\Contracts\API\PageContract;

class PageService
{
    /**
     * @var PageContract
     */
    protected $pageRepository;

    /**
     * PageService constructor.
     *
     * @param PageContract $pageRepository
     */
    public function __construct(PageContract $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * Get all static pages with optional sorting/filtering.
     *
     * @param array $params
     * @return mixed
     */
    public function fetchPages(array $params = []): mixed
    {
        $order   = $params['order'] ?? 'id';
        $sort    = $params['sort'] ?? 'desc';
        $columns = $params['columns'] ?? ['*'];

        return $this->pageRepository->listPages($order, $sort, $columns);
    }

    /**
     * Get full page details by slug.
     *
     * @param string $slug
     * @return mixed
     */
    public function findPageBySlug(string $slug): mixed
    {
        return $this->pageRepository->findPageBySlug($slug);
    }

    /**
     * Get only other pages.
     *
     * @param array $columns
     * @param array $filter
     * @return mixed
     */
    public function getOtherPages(array $columns = ['*'], array $filters = []): mixed
    {
        return $this->pageRepository->filteredPages($columns, $filters);
    }
}
