<?php

namespace App\Helpers;

use App\Models\MetaData;

class Settings
{
    public static function get($key, $default = false)
    {
        $key = MetaData::where('key', $key)->first();
        if ($key) {
            return $key->value;
        }
        else {
            return $default;
        }
    }
}