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

class CacheForget implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $key;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Storage::delete('caches/' . $this->key . '.json');
        $cache = DB::table('page_caches')->where('key', $this->key)->delete();
    }
}
