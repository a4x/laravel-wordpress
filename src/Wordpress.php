<?php

namespace A440\Wordpress;

use Cache;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Wordpress {

    protected $posts;
    protected $categories;

    public function categories() {
        return $this->categories;
    }

    public function posts() {
        return $this->posts;
    }

    public function __construct() {
        $this->posts = Cache::get('a440:wordpress:posts', collect([]));
        $this->categories = Cache::get('a440:wordpress:categories', collect([]));
    }

    private static function wp_get($type, $per_page = -1) {
        // should put in for loop for count -1
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://'.config('wordpress.url').'?json='.$type.'&count='.$per_page);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            throw new HttpException;
        } else {
            $result = json_decode($response, true);
            curl_close($ch);
            return $result;
        }
    }

    public static function refresh() {
        Cache::forever('a440:wordpress:posts', collect(self::wp_get('get_posts')['posts']));

        Cache::forever('a440:wordpress:categories', collect(self::wp_get('get_category_index')['categories']));
    }
}