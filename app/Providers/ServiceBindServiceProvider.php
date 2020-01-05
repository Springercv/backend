<?php

namespace App\Providers;

use App\Services\Interfaces\MailServiceInterface;
use App\Services\Interfaces\SearchServiceInterface;
use App\Services\MailService;
use App\Services\SearchService;
use Illuminate\Support\ServiceProvider;
use App\Services\ImageService;
use App\Services\Interfaces\ImageServiceInterface;

class ServiceBindServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            ImageServiceInterface::class,
            ImageService::class
        );

        $this->app->singleton(
            SearchServiceInterface::class,
            SearchService::class
        );

        $this->app->singleton(
            MailServiceInterface::class,
            MailService::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
