<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Location\City;

class Places extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:places';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape places using Google Map Place Api';

    private $city;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scrapingMode = [
            'Kota tertentu',
            'Random',
        ];
        $mode = $this->choice('Mode scraping', $scrapingMode);

        if ($mode == $scrapingMode[0]) {
            $cities = $this->ask('Masukkan nama kota (pisahkan dengan tanda koma)');
            $cities = explode(',', $cities);
            foreach ($cities as $city) {
                $city = trim($city);
                $city = City::where('name', 'like', $city)
                    ->where('is_scraped', 'N')
                    ->first();

                if ($city) {
                    $this->line('[ * ] Scraping tempat di ' . $city->name);
                    $this->city = $city;
                    $city->update(['is_scraped' => 'PROCESS']);
                    $this->scrapePlaces();
                    $city->update(['is_scraped' => 'Y']);
                }
                else {
                    $this->line('[ * ] Kota tidak ada');
                }

                $this->line('--------------------');
            } // [END] foreach
        }
        else {
            $limit = $this->ask('Limit scraping', 10);
            for ($i = 1; $i <= $limit; $i++) {
                $city = City::where('is_scraped', 'N')
                    ->orderBy('id', 'asc')
                    ->first();
                if ($city) {
                    $this->line('[ * ] Scraping tempat di ' . $city->name);
                    $this->city = $city;
                    $city->update(['is_scraped' => 'PROCESS']);
                    $this->scrapePlaces();
                    $city->update(['is_scraped' => 'Y']);
                }
                else {
                    break;
                }
                $this->line('--------------------');
            } // [END] for
        }
    }

    private function scrapePlaces()
    {

    }
}
