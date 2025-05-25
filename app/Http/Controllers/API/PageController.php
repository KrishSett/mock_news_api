<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\PageService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

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
        $cached = Cache::get(config('apicachekeys.pages.list'));
        if ($cached) {
            return $cached;
        }

        $pages = $this->pageService->fetchPages([
            'order'   => 'list_order',
            'sort'    => 'asc',
            'columns' => ['id', 'name', 'slug']
        ]);

        if (!empty($pages)) {
            $ttl = config('apicachekeys.expiry', 60);
            Cache::put(config('apicachekeys.pages.list'), $pages, $ttl);
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
        $cached = Cache::get(config('apicachekeys.pages.others'));
        if ($cached) {
            return $cached;
        }

        $pages = $this->pageService->getOtherPages([
            'slug',
            'name'
        ], [
            'footer_link' => false
        ]);

        if (!empty($pages)) {
            $ttl = config('apicachekeys.expiry', 60);
            Cache::put(config('apicachekeys.pages.others'), $pages, $ttl);
            return $this->responseSuccess(
                $pages,
                200
            );
        }

        return $this->responseError('No pages or error must have been occurred while retrieving the pages', 400);
    }
}