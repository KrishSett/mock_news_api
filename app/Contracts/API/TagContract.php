<?php
namespace App\Contracts\API;

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
}
