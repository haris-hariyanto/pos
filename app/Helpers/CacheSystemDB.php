<?php

/**
 * Sistem cache yang baru, menggunakan database dan storage
 */

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CacheSystemDB
{
    public static function generate($key, $data = [], $cacheTags = [], $otherTags = [], $expiredDays = 0)
    {
        if ($expiredDays > 0) {
            $expiredDays = 7 * 60 * 60;
            $expiredDays = time() + $expiredDays;
        }

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
            ['value' => $cacheData, 'tags' => $tags, 'expiration' => $expiredDays],
        );
        Storage::put('caches/' . $key . '.json', $cacheData);
    }

    public static function get($key)
    {
        // return false;
        if (Storage::exists('caches/' . $key . '.json')) {
            $cacheData = Storage::get('caches/' . $key . '.json');
            return json_decode($cacheData, true);
        }
        
        $cache = DB::table('page_caches')->where('key', $key)
            ->first();
        if ($cache) {
            $currentTime = time();
            if ($cache->expiration > 0 && $currentTime > $cache->expiration) {
                DB::table('page_caches')->where('key', $key)->delete();
            }
            return json_decode($cache->value, true);
        }
        else {
            return false;
        }
    }

    public static function forgetWithTags($tags, $tagType = false)
    {
        if (is_array($tags)) {
            // Delete file cache
            $caches = DB::table('page_caches')->select('key')
            ->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->where('tags', 'like', '%' . $tag . '%');
                }
            })->get();
            $keys = [];
            foreach ($caches as $cache) {
                $keys[] = 'caches/' . $cache->key . '.json';
            }
            Storage::delete($keys);
            // [END] Delete file cache

            DB::table('page_caches')->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->where('tags', 'like', '%' . $tag . '%');
                }
            })->delete();
        }
        else {
            if ($tagType) {
                // Delete file cache
                $caches = DB::table('page_caches')->select('key')->where('tags', 'like', '%[' . $tagType . ':' . $tags . ']%')->get();
                $keys = [];
                foreach ($caches as $cache) {
                    $keys[] = 'caches/' . $cache->key . '.json';
                }
                Storage::delete($keys);
                // [END] Delete file cache

                DB::table('page_caches')->where('tags', 'like', '%[' . $tagType . ':' . $tags . ']%')->delete();
            }
            else {
                // Delete file cache
                $caches = DB::table('page_caches')->select('key')->where('tags', 'like', '%' . $tags . '%')->get();
                $keys = [];
                foreach ($caches as $cache) {
                    $keys[] = 'caches/' . $cache->key . '.json';
                }
                Storage::delete($keys);
                // [END] Delete file cache

                DB::table('page_caches')->where('tags', 'like', '%' . $tags . '%')->delete();
            }
        }
    }

    public static function forget($key)
    {
        Storage::delete('caches/' . $key . '.json');
        $cache = DB::table('page_caches')->where('key', $key)->delete();
    }

    public static function flush()
    {
        Storage::deleteDirectory('caches');
        DB::table('page_caches')->truncate();
    }
}