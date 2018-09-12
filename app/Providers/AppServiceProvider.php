<?php

namespace App\Providers;

use App\Services\BasketService;use App\Services\DiscountService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Services\DiscountServiceInterface', function () {
            return new DiscountService();
        });

        $this->app->bind('App\Services\BasketServiceInterface', function () {
            return new BasketService();
        });
    }
}
