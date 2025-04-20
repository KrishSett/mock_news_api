<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Services\API\NewsService;
use Illuminate\Http\Request;
use App\Models\API\News;

class NewsController extends ApiBaseController
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        parent::__construct();
        $this->newsService = $newsService;
    }

    public function getNews(Request $request, $uuid)
    {
        $news = $this->newsService->getNews($uuid);

        if (!empty($news)) {
            return $this->responseSuccess($news, 200);
        }

        return $this->responseError("No news found.");
    }
}
