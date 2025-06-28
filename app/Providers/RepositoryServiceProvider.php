<?php

namespace App\Providers;

use App\Contracts\API\CategoryContract;
use App\Contracts\API\GuestTokenContract;
use App\Contracts\API\HeaderHashContract;
use App\Contracts\API\LatestContentContract;
use App\Contracts\API\NewsContract;
use App\Contracts\API\PageContract;
use App\Contracts\API\SubcategoryContract;
use App\Contracts\API\TagContract;
use App\Repositories\API\CategoryRepository;
use App\Repositories\API\GuestTokenRepository;
use App\Repositories\API\LatestContentRepository;
use App\Repositories\API\PageRepository;
use App\Repositories\API\SubcategoryRepository;
use App\Repositories\API\NewsRepository;
use App\Repositories\API\HeaderHashRepository;
use App\Repositories\API\TagRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        CategoryContract::class      => CategoryRepository::class,
        SubcategoryContract::class   => SubcategoryRepository::class,
        NewsContract::class          => NewsRepository::class,
        GuestTokenContract::class    => GuestTokenRepository::class,
        HeaderHashContract::class    => HeaderHashRepository::class,
        PageContract::class          => PageRepository::class,
        TagContract::class           => TagRepository::class,
        LatestContentContract::class => LatestContentRepository::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->repositories as $interface => $implementation)
        {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
