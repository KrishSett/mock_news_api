<?php


namespace App\Services\API;


use App\Contracts\API\TagContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagService
{
    /**
     * @var TagContract
     */
    protected $tagRepository;

    /**
     * TagService constructor.
     *
     * @param TagContract $tagRepository\
     */
    public function __construct(TagContract $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * List of all tags based on filter.
     *
     * @param array $filter
     * @return mixed
     */
    public function listTags(array $filter)
    {
        return $this->tagRepository->listTags($filter);
    }

    /**
     * Create a tag.
     *
     * @param array $attributes
     * @return mixed
     */
    public function createTag(array $attributes): mixed
    {
        return $this->tagRepository->createTag($attributes);
    }

    /**
     * Get all newses related to given tag(s)
     * 
     * @param array $tags
     * @return AnonymousResourceCollection
     */
    public function tagNews(array $tags): AnonymousResourceCollection
    {
        return $this->tagRepository->tagNews($tags);
    }
}
