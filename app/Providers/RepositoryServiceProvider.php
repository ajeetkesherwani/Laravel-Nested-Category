<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
