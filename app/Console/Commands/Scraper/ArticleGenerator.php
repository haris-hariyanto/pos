<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Models\Key;

class ArticleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:articles';

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
        $this->line('[ * ] Menghitung hotel...');

        $hotelsTotal = Hotel::count();
        $hotelsWithoutArticle = Hotel::where('article', '')
            ->orWhereNull('article')
            ->count();
        $hotelsWithArticle = Hotel::where('article', '<>', '')
            ->whereNotNull('article')
            ->count();
        
        $this->info('[ * ] Jumlah hotel : ' . $hotelsTotal);
        $this->info('[ * ] Hotel dengan artikel : ' . $hotelsWithArticle);
        $this->info('[ * ] Hotel tanpa artikel : ' . $hotelsWithoutArticle);
        $this->line('--------------------');

        $limit = $this->ask('Limit Hotel', 1000);

        $this->initializeKeys();

        for ($i = 1; $i <= $limit; $i++) {
            $hotel = Hotel::where('article', '')
                ->orWhereNull('article')
                ->orderBy('total_views', 'DESC')
                ->orderBy('number_of_reviews')
                ->first();
            
            if (!$hotel) {
                $this->info('[ * ] Semua hotel sudah memiliki artikel');
                return 0;
            }

            $this->info('[ ' . $i . '/' . $limit . ' ] Membuat artikel : ' . $hotel->name);
        } // [END] for
    }

    private function initializeKeys()
    {
        $path = base_path('openai_keys.txt');
        $keys = file_get_contents($path);
        $keys = preg_split('/\r\n|\r|\n/', $keys);

        Key::where('id', '>', 0)->update(['is_exists_in_file' => 'N']);

        foreach ($keys as $key) {
            if (empty(trim($key))) {
                continue;
            }
            
            if (Key::where('key', $key)->exists()) {
                Key::where('key', $key)->update(['is_exists_in_file' => 'Y']);
            }
            else {
                Key::create([
                    'key' => $key,
                    'is_broken' => 'N',
                    'is_last_used' => 'N',
                    'is_exists_in_file' => 'Y',
                ]);
            }
        } // [END] foreach

        Key::where('is_exists_in_file', 'N')->delete();
    }
}
