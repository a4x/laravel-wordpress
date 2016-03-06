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

        config([
            'config/wordpress.php',
        ]);
    }
}
