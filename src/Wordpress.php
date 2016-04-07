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
        $this->posts = Cache::rememberForever('a440:wordpress:posts', function() {
            return collect(self::wp_get('get_posts')['posts']);
        });

        $this->categories = Cache::rememberForever('a440:wordpress:categories', function() {
            return collect(self::wp_get('get_category_index')['categories']);
        });

        Cache::remember('a440:wordpress:last_updated_', config('wordpress.refresh'), function() {
            $last_post_id = $this->posts->max('id');
            $posts = collect(self::wp_get('get_posts', config('wordpress.checklastnposts'))['posts']);

            $posts->reverse()->each(function($item, $key) use ($last_post_id){
                if ($item['id'] > $last_post_id) {
                    $this->posts->prepend($item);
                }
            });

            return true;
        });
    }

    private static function wp_get($type, $per_page = -1) {
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
}