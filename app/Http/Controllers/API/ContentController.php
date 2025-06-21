<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Models\API\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentController extends ApiBaseController
{
    /**
     * Create contents.
     *
     * @param Request $request
     */
    public function createContent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => ["required", "string", "max:100"],
            "body"  => ["required", "string", "max:1000"],
            "topic_id" => ["required", "integer", "exists:topics,id"],
        ], [
            "topic_id.exists" => "Selected topic id is either invalid or not found in our records."
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors()->first());
        }

        $content = Content::create($request->all());
        return $this->responseSuccess(['success' => true, 'content'=> $content->id]);
    }
}
