<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\EventServiceInterface;
use App\Services\EventService;
use App\Services\SecureProxyEventService;

class SecureProxyEventServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EventService::class, fn() => new EventService());

        $this->app->bind(EventServiceInterface::class, function ($app) {
            return new SecureProxyEventService($app->make(EventService::class));
        });
    }

    public function boot(): void
    {
        //
    }
}

?>