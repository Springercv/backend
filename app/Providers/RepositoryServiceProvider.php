<?php

namespace App\Providers;

use App\Repositories\CityRepository;
use App\Repositories\CountryRepository;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\LocationRepositoryInterface;
use App\Repositories\Interfaces\MasterSearchRepositoryInterface;
use App\Repositories\Interfaces\PriceRepositoryInterface;
use App\Repositories\Interfaces\TourRepositoryInterface;
use App\Repositories\LocationRepository;
use App\Repositories\MasterSearchRepository;
use App\Repositories\PriceRepository;
use App\Repositories\TourRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use App\Repositories\ImageRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CityRepositoryInterface::class,
            CityRepository::class
        );

        $this->app->bind(
            CountryRepositoryInterface::class,
            CountryRepository::class
        );

        $this->app->bind(
            ImageRepositoryInterface::class,
            ImageRepository::class
        );

        $this->app->bind(
            TourRepositoryInterface::class,
            TourRepository::class
        );

        $this->app->bind(
            LocationRepositoryInterface::class,
            LocationRepository::class
        );

        $this->app->bind(
            MasterSearchRepositoryInterface::class,
            MasterSearchRepository::class
        );

        $this->app->bind(
            PriceRepositoryInterface::class,
            PriceRepository::class
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