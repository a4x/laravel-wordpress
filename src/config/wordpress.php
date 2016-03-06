<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Wordpress config
    |--------------------------------------------------------------------------
    |
    |
    */

    'url'               => env('WORDPRESS_URL', 'localhost'),
    'refresh'           => env('WORDPRESS_REFRESH', 10),
    'checklastnposts'   => env('WORDPRESS_CHECKLASTNPOSTS', 10),

];
