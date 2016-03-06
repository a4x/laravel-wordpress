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
        $this->publishes([
            __DIR__.'/config/wordpress.php' => config_path('wordpress.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('wordpress', function($app){
            return new Wordpress($app);
        });
    }
}
