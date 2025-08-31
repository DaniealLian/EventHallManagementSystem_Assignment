<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\UserFactoryInterface;
use App\Contracts\EventManagerFactoryInterface;
use App\Factories\UserFactory;
use App\Factories\EventManagerFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserFactoryInterface::class, UserFactory::class);
        // $this->app->bind(EventManagerFactoryInterface::class, EventManagerFactory::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
