<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Services\API\NewsService;
use Illuminate\Http\Request;

class HomePageController extends ApiBaseController
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function getContents(Request $request)
    {
        $homeContents = $this->newsService->getLatestNews();
        return $this->responseSuccess($homeContents, 200);
    }
}
