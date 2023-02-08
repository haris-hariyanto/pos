<?php

namespace App\Helpers;

class Image
{
    public static function removeQueryParameters($imageLink)
    {
        if (config('app.env') === 'local') {
            return $imageLink;
        }
        else {
            $imageLink = 'https://' . parse_url($imageLink, PHP_URL_HOST) . parse_url($imageLink, PHP_URL_PATH);
        }
        return $imageLink;
    }
}