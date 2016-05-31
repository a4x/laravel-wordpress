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
            $response = str_replace(config('wordpress.replace_from'), config('wordpress.replace_to'), $response);
            $result = json_decode($response, true);
            curl_close($ch);
            return $result;
        }
    }

    public function getPost($id) {
        return Cache::get('a440:wordpress:posts_'.$id);
    }

    public static function refresh() {
        //delete all previous posts
        if (\Config('cache.default') == 'redis') {
            Redis::pipeline(function ($pipe) {
                foreach (Redis::keys('laravel:a440:wordpress:posts_*') as $key) {
                    $pipe->del($key);
                }
            });
        }

        $posts = collect(self::wp_get('get_posts')['posts'])->each(function($item) {
            Cache::forever('a440:wordpress:posts_'.$item['id'], $item);
        });

        Cache::forever('a440:wordpress:posts', $posts->transform(function($item) {
            unset($item['content']);
            unset($item['url']);
            unset($item['status']);
            unset($item['title_plain']);
            unset($item['modified']);
            unset($item['categories']);
            unset($item['comments']);
            unset($item['attachments']);
            unset($item['comment_count']);
            unset($item['comment_status']);
            unset($item['thumbnail']);
            unset($item['custom_fields']);
            unset($item['thumbnail_size']);
            unset($item['thumbnail_images']['full']);
            unset($item['thumbnail_images']['thumbnail']);
            unset($item['thumbnail_images']['post-thumbnail']);
            return $item;
        }));

        Cache::forever('a440:wordpress:categories', collect(self::wp_get('get_category_index')['categories']));
    }
}