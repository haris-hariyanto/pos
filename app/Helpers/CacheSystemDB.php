<?php

/**
 * Sistem cache yang baru, menggunakan database
 */

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class CacheSystemDB
{
    public static function generate($key, $data = [], $cacheTags = [], $otherTags = [])
    {
        $cacheData = json_encode($data);

        $tags = [];
        foreach ($cacheTags as $cacheTag => $cacheTagType) {
            $dataTagItems = $data[$cacheTag];
            foreach ($dataTagItems as $dataTagItem) {

                if ($cacheTagType == 'continent') {
                    $tags[] = '[continent:' . $dataTagItem['id'] . ']';
                }

                if ($cacheTagType == 'place') {
                    $tags[] = '[place:' . $dataTagItem['id'] . ']';
                }

                if ($cacheTagType == 'hotel') {
                    $tags[] = '[hotel:' . $dataTagItem['id'] . ']';
                }

                if ($cacheTagType == 'city') {
                    $tags[] = '[city:' . $dataTagItem['id'] . ']';
                }

                if ($cacheTagType == 'state') {
                    $tags[] = '[state:' . $dataTagItem['id'] . ']';
                }
            }
        } // [END] foreach
        $tags = array_merge($tags, $otherTags);
        // dd($tags);
        $tags = implode('', $tags);

        DB::table('page_caches')->updateOrInsert(
            ['key' => $key],
            ['value' => $cacheData, 'tags' => $tags]
        );
    }

    public static function get($key)
    {
        $cache = DB::table('page_caches')->where('key', $key)
            ->first();
        if ($cache) {
            return json_decode($cache->value, true);
        }
        else {
            return false;
        }
    }

    public static function forgetWithTags($tags, $tagType = false)
    {
        if (is_array($tags)) {
            DB::table('page_caches')->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->where('tags', 'like', '%' . $tag . '%');
                }
            })->delete();
        }
        else {
            if ($tagType) {
                DB::table('page_caches')->where('tags', 'like', '%[' . $tagType . ':' . $tags . ']%')->delete();
            }
            else {
                DB::table('page_caches')->where('tags', 'like', '%' . $tags . '%')->delete();
            }
        }
    }

    public static function forget($key)
    {
        $cache = DB::table('page_caches')->where('key', $key)->delete();
    }

    public static function flush()
    {
        DB::table('page_caches')->truncate();
    }
}