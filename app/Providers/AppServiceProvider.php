<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\UserFactoryInterface;
use App\Contracts\AdminFactoryInterface;
use App\Factories\UserFactory;
use App\Factories\AdminFactory;
use App\Services\VenueService;
use App\Contracts\EventServiceInterface;
use App\Services\SecureProxyEventService;
use App\Services\EventService;

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
    
        $this->app->singleton('venueService', function ($app) {
        return new VenueService();

        $this->app->bind(EventServiceInterface::class, function($app){
            return new SecureProxyEventService(
                $app->make(EventService::class)
            );
        });

        $this->app->singleton(\App\Services\EventService::class);
    
        $this->app->bind(\App\Contracts\EventServiceInterface::class, function ($app) {
            return new \App\Services\SecureProxyEventService(
                $app->make(\App\Services\EventService::class)
            );
        });
    });

    
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
