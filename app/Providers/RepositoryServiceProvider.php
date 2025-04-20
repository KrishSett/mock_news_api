<?php

namespace App\Providers;

use App\Contracts\API\CategoryContract;
use App\Contracts\API\NewsContrtact;
use App\Contracts\API\SubcategoryContract;
use App\Repositories\API\CategoryRepository;
use App\Repositories\API\SubcategoryRepository;
use App\Repositories\API\NewsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    protected $repositories = [
        CategoryContract::class => CategoryRepository::class,
        SubcategoryContract::class => SubcategoryRepository::class,
        NewsContrtact::class => NewsRepository::class,
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
