<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Services\API\NewsService;
use Illuminate\Http\Request;

class HomePageController extends ApiBaseController
{
    /**
     * @var NewsService
     */
    protected $newsService;

    /**
     * HomePageController constructor.
     *
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService)
    {
        parent::__construct();
        $this->newsService = $newsService;
    }

    /**
     * Get home page contents.
     *
     * @param Request $request
     */
    public function getContents(Request $request)
    {
        $homeContents = $this->newsService->getLatestNews();
        return $this->responseSuccess($homeContents, 200);
    }
}
