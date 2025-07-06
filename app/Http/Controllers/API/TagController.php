<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class TagController extends ApiBaseController
{
    /**
     * @var TagService
     */
    protected $tagService;

    /**
     * TagController constructor.
     *
     * @param TagService $tagService
     */
    public function __construct(TagService $tagService)
    {
        parent::__construct();
        $this->tagService = $tagService;
    }

    /**
     * List of all tags.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function listTags(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'in:active,inactive,all']
        ]);

        if ($validator->fails()) {
            return $this->responseValidationError($validator->errors()->all());
        }

        $cached = Cache::get(config('apicachekeys.tags.list'));
        if ($cached) {
            return $cached;
        }

        $tags = $this->tagService->listTags($request->all());
        if ($tags) {
            Cache::put(config('apicachekeys.tags.list'), $tags, config('apicachekeys.expiry'));
        }

        return $tags;
    }

    /**
     * Create a new tag.
     *
     * @param Request $request
     */
    public function createTag(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => ['required', 'min:2', 'max:20', 'unique:tags,name'],
            'description' => ['required', 'min:5', 'max:100'],
            'active'      => ['required', 'boolean']
        ]);

        if ($validator->fails()) {
            return $this->responseValidationError($validator->errors()->all());
        }

        $created = $this->tagService->createTag($request->all());

        if (!$created) {
            return $this->responseError('Failed to create tags.');
        }

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Tag created successfully.'
        ], 200);
    }

    /**
     * Get tag relates newses
     * 
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function tagNews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tags' => ['required', 'array']
        ]);

        if ($validator->fails()) {
            return $this->responseValidationError($validator->errors()->all());
        }

        $tagNews = $this->tagService->tagNews($request->tags);
        return $tagNews;
    }
}
