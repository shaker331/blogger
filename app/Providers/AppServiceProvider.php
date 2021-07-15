<?php

namespace App\Providers;
use App\Category;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        view()->share('laravel',Category::find(1));
        view()->share('android',Category::find(3));
        view()->share('paython',Category::find(2));
        view()->share('cricket',Category::find(4));
    }
}
