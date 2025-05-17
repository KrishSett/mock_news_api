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
     * Instance of the class
     * 
     * @param \App\Contracts\API\NewsContract $newsRepository
     */
    public function __construct(NewsContract $newsContract)
    {
        $this->newsRepository = $newsContract;
    }

    public function getNews(string $uuid): mixed
    {
        return $this->newsRepository->getNews($uuid);
    }

    public function getLatestNews(): mixed
    {
        return $this->newsRepository->latestNews();
    }
}