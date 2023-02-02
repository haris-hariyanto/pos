<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class CacheSystem
{
    public static function generate($key, $data = [])
    {
        $data = json_encode($data);
        Cache::forever($key, $data);
    }

    public static function get($key)
    {
        return false;
        $cacheData = Cache::get($key);
        if ($cacheData) {
            return json_decode($cacheData, true);
        }
        else {
            return false;
        }
    }
}