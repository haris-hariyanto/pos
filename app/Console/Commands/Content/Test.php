<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Chain;
use App\Models\Hotel\Brand;
use App\Models\Hotel\HotelPlace;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\City;
use App\Models\Location\Place;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:test';

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
        /*
        $hotels = Hotel::with('brand')->limit(1000)->get();
        foreach ($hotels as $hotel) {
            $this->line($hotel->brand->name);
        }
        */
        Chain::truncate();
        Brand::truncate();
        HotelPlace::truncate();
        Continent::truncate();
        Country::truncate();
        State::truncate();
        City::truncate();
        Place::truncate();
    }
}
