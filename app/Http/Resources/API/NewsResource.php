<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid'              => $this->uuid,
            'title'             => $this->title,
            'short_description' => $this->short_description,
            'thumbnail'         => $this->thumbnail,
            'created_at'        => \Carbon\Carbon::parse($this->created_at)->format('F j, Y')
        ];
    }
}
