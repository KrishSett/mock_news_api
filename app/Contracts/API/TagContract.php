<?php
namespace App\Contracts\API;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Interface TagContract
 *
 * @package App\Contracts\API\TagContract
 */
interface TagContract
{
    /**
     * @param array $filter
     * @return mixed
     */
    public function listTags(array $filter): mixed;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function createTag(array $attributes): mixed;

    /**
     * @param array $tags
     * @param string|null $exceptNewsId;
     * @return AnonymousResourceCollection
     */
    public function tagNews(array $tags, ?string $exceptNewsId = null): AnonymousResourceCollection;
}
