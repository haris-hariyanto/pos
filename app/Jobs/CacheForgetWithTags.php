<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CacheForgetWithTags implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tags;
    public $tagType;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tags, $tagType = false)
    {
        $this->tags = $tags;
        $this->tagType = $tagType;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tags = $this->tags;
        $tagType = $this->tagType;

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
}
