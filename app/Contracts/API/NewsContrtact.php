<?php

namespace App\Contracts\API;

interface NewsContrtact
{
    public function getNews(string $uuid): mixed;
}