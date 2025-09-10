<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\UserFactoryInterface;
use App\Contracts\AdminFactoryInterface;
use App\Factories\UserFactory;
use App\Factories\AdminFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // User factory binding (for customers and managers)
        $this->app->bind(UserFactoryInterface::class, UserFactory::class);

        // Admin factory binding (for admin panel)
        $this->app->bind(AdminFactoryInterface::class, AdminFactory::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
