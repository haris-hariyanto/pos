<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Key;

class OpenAIKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:articles:keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Hijau = Bisa digunakan');
        $this->error('Merah = Tidak bisa digunakan');
        $this->line('--------------------');
        $this->newLine(2);

        $keys = Key::get();
        foreach ($keys as $key) {
            if ($key->is_broken == 'N') {
                $this->info('[ * ] ' . $key->key);
            }
            else {
                $this->error('[ * ] ' . $key->key);
            }
        }
    }
}
