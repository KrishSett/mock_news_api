<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Services\API\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends ApiBaseController
{
    protected $tagService;

    public function __construct(TagService $tagService)
    {
        parent::__construct();
        $this->tagService = $tagService;
    }

    public function listTags(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'in:active,all']
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors()->first());
        }

        return $this->tagService->listTags($request->all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createTag(Request $request)
    {
        //
    }
}
