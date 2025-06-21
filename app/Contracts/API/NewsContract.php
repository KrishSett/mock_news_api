<?php

namespace App\Contracts\API;

/**
 * Interface NewsContract
 *
 * @package App\Contracts\API\NewsContract
 */
interface NewsContract
{
    /**
     * @param string $uuid
     * @return mixed
     */
    public function getNews(string $uuid): mixed;

    /**
     * @return mixed
     */
    public function latestNews(): mixed;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function createNews(array $attributes):mixed;
}
