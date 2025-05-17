<?php

namespace App\Contracts\API;

/**
 * Interface NewsContract
 *
 * @package App\Contracts\API\NewsContract
 */
interface NewsContract
{
    public function getNews(string $uuid): mixed;

    public function latestNews(): mixed;
}