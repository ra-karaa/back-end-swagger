<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\ApiRepoInterfaces;
use App\Repository\ApiRepo;

class ApiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ApiRepoInterfaces::class, ApiRepo::class);
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
