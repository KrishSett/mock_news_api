<?php


namespace App\Contracts\API;

/**
 * Interface LatestContentContract
 * @package App\Contracts\API
 */
interface LatestContentContract
{
    /**
     * @param array $attr
     * @return mixed
     */
    public function createLatestContent(array $attr): mixed;
}
