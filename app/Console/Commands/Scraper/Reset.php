<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Country;

class Reset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use this command after add a new place type in the config';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Country::where('is_scraped', 'Y')->orWhere('is_scraped', 'PROCESS')->update([
            'is_scraped' => 'N',
        ]);
        $this->info('Done!');
    }
}
