<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Location\Place;
use App\Models\Location\City;
use App\Models\Location\State;
use Illuminate\Support\Facades\Cache;

class PriceUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:price-update';

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
        $this->line('[ * ] Menghapus cache harga ...');

        $places = Place::get();
        foreach ($places as $place) {
            Cache::forget('place' . $place->slug . 'lowest-price');
        }

        $cities = City::get();
        foreach ($cities as $city) {
            Cache::forget('locationcity' . $city->slug . 'lowest-price');
            Cache::forget('location' . config('content.location_term_city') . $city->slug . 'lowest-price');
        }

        $states = State::get();
        foreach ($states as $state) {
            Cache::forget('locationstate' . $state->slug . 'lowest-price');
            Cache::forget('location' . config('content.location_term_state') . $state->slug . 'lowest-price');
        }

        $this->line('[ * ] Selesai');
    }
}
