<?php

namespace App\Providers;

use App\Interfaces\groundServiceRepositoryInterface;
use App\Repositories\groudServiceRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->bind(groundServiceRepositoryInterface::class, groudServiceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
