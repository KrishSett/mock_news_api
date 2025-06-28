<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\LatestContentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LatestContentController extends ApiBaseController
{
    /**
     * @var LatestContentService
     */
    protected $latestContentService;

    /**
     * LatestContentController constructor.
     *
     * @param LatestContentService $latestContentService
     */
    public function __construct(LatestContentService $latestContentService)
    {
        parent::__construct();
        $this->latestContentService = $latestContentService;
    }

    public function createLatestContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'news_id' => ['required', 'integer', 'exists:news,id', 'unique:latest_contents,news_id'],
            'order' => ['required', 'integer', 'min:1'],
            'active' => ['required', 'boolean']
        ]);

        if ($validator->fails()) {
            return $this->responseValidationError($validator->errors()->toArray());
        }

        $created = $this->latestContentService->createLatestContent(($request->all()));

        if (!$created) {
            return $this->responseError('Failed to set latest content');
        }

        return $this->responseSuccess([
            'success' => true,
            'message' => 'Latest content has been successfully updated'
        ]);
    }
}
