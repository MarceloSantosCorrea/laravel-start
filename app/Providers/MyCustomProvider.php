<?php

namespace App\Providers;

use App\Helpers\MyHelpers;
use Illuminate\Support\ServiceProvider;

class MyCustomProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MyHelpers::class, function () {
            return new MyHelpers();
        });
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
