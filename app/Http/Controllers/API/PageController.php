<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\PageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PageController extends ApiBaseController
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    /**
     * Get all active pages
     * 
     * @param Request $request
     */
    public function fetchPages(Request $request)
    {
        $pages = $this->pageService->fetchPages([
            'order'   => 'list_order',
            'sort'    => 'asc',
            'columns' => ['id', 'name', 'slug']
        ]);

        if (!empty($pages)) {
            return $this->responseSuccess(
                $pages,
                200
            );
        }

        return $this->responseError('Error retrieving pages', 400);
    }

    /**
     * Get single page by slug
     * 
     * @param Request $request
     * @param string $slug
     */
    public function getPage(Request $request, string $slug)
    {
        $page = $this->pageService->findPageBySlug($slug);

        if (empty($page)) {
            return $this->responseError('Page not found', 400);
        }

        return $this->responseSuccess($page);
    }

    /**
     * Get only footer pages (convenience endpoint)
     */
    public function otherPages()
    {
        $pages = $this->pageService->getOtherPages([
            'slug',
            'name'
        ], [
            'footer_link' => false
        ]);
        return $this->responseSuccess($pages);
    }
}