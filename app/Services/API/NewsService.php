<?php

namespace App\Services\API;

use App\Contracts\API\NewsContrtact;

class NewsService 
{
    /**
     * @var NewsContrtact 
     */
    protected $newsRepository;

    /**
     * Instance of the class
     * 
     * @param \App\Contracts\API\NewsContrtact $newsRepository
     */
    public function __construct(NewsContrtact $newsContrtact)
    {
        $this->newsRepository = $newsContrtact;
    }

    public function getNews(string $uuid): mixed
    {
        return $this->newsRepository->getNews($uuid);
    }
}