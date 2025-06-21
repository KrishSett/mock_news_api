<?php

namespace App\Services\API;

use App\Contracts\API\NewsContract;

class NewsService
{
    /**
     * @var NewsContract
     */
    protected $newsRepository;

    /**
     * NewsService constructor.
     *
     * @param NewsContract $newsContract
     */
    public function __construct(NewsContract $newsContract)
    {
        $this->newsRepository = $newsContract;
    }

    /**
     * Get news details.
     *
     * @param string $uuid
     * @return mixed
     */
    public function getNews(string $uuid): mixed
    {
        return $this->newsRepository->getNews($uuid);
    }

    /**
     * Get latest news.
     *
     * @return mixed
     */
    public function getLatestNews(): mixed
    {
        return $this->newsRepository->latestNews();
    }

    /**
     * Create news.
     *
     * @param array $attr
     * @return mixed
     */
    public function createNews(array $attr): mixed
    {
        return $this->newsRepository->createNews($attr);
    }
}
