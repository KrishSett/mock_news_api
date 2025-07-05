<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Http\Controllers\Controller;
use App\Services\API\NewsService;
use App\Services\API\TagService;
use Illuminate\Http\Request;
use App\Models\API\News;
use Illuminate\Support\Facades\Validator;

class NewsController extends ApiBaseController
{
    /**
     * @var NewsService
     */
    protected $newsService;

    /**
     * @var TagService
     */
    protected $tagService;

    /**
     * NewsController constructor.
     *
     * @param NewsService $newsService
     */
    public function __construct(NewsService $newsService, TagService $tagService)
    {
        parent::__construct();
        $this->newsService = $newsService;
        $this->tagService  = $tagService;
    }

    /**
     * Get news details.
     *
     * @param Request $request
     * @param $uuid
     */
    public function getNews(Request $request, $uuid)
    {
        $news = $this->newsService->getNews($uuid);

        if (!empty($news)) {
            $tags = collect($news['tags'])->pluck('slug')->toArray();
            $news['related_news'] = $this->tagService->tagNews($tags);

            return $this->responseSuccess($news, 200);
        }

        return $this->responseError("No news found.");
    }

    /**
     * Create a news.
     *
     * @param Request $request
     */
    public function createNews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subcategory_id'    => ['required', 'integer', 'exists:subcategories,id'],
            'title'             => ['required', 'min:10', 'max:100'],
            'short_description' => ['required', 'min:50', 'max:200'],
            'description'       => ['required', 'min:100', 'max:2000'],
            'thumbnail'         => ['nullable', 'image', 'mimes:jpg,jpeg'],
            'tags'              => ['required', 'array'],
            'tags.*.id'         => ['required', 'integer', 'exists:tags,id']
        ]);

        if ($validator->fails()) {
            return $this->responseValidationError($validator->errors()->all());
        }

        $params = $request->all();
        $created = $this->newsService->createNews($params);

        if (!$created) {
            return $this->responseError('Failed to create news.', 500);
        }

        return $this->responseSuccess([
            'success' => true,
            'message' => 'News created successfully.'
        ], 200);
    }
}
