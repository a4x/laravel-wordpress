<?php

namespace A440\Wordpress;

use Illuminate\Support\ServiceProvider;

class WordpressServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('A440\Wordpress\Wordpress');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
