# Laravel-Wordpress

This library wraps your blog posts into a Laravel collection.

## Installation

```bash
composer require "a4x/laravel-wordpress:0.0.1-beta"
```

Once this has finished, you will need to add the service provider to the `providers` array in your `app.php` config as follows:

```php
'A440\Wordpress\WordpressServiceProvider'
```

Finally, you will want to publish the config using the following command:

```bash
php artisan vendor:publish --provider="A440\Wordpress\WordpressServiceProvider"
```

Then, update your `config/wordpress.php` file with your Wordpress installation URL or IP.

And you're done!

## Basic Usage

```php
<?php
namespace App\Http\Controllers;

use A440\Wordpress\Wordpress;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Wordpress $wp)
    {
        $posts = $wp->posts()
            ->sortByDesc('date')
            ->where('author.name', 'Matthew Crist')
            ->forPage(1, 4);

        dd($posts);
    }
}
```

Now you can access your blog posts and categories just like from an Eloquent model.
