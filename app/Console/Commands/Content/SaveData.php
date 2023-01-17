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
use Illuminate\Support\Str;

class SaveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:savedetail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function createUniqueSlug($str, $type)
    {
        $baseSlug = Str::slug($str);
        $finalSlug = $baseSlug;
        $tailID = 1;
        $loop = true;
        while ($loop) {
            switch ($type) {
                case 'city':
                    $slugCount = City::where('slug', $finalSlug)->count();
                    break;
                case 'state':
                    $slugCount = State::where('slug', $finalSlug)->count();
                    break;
                case 'country':
                    $slugCount = Country::where('slug', $finalSlug)->count();
                    break;
                case 'continent':
                    $slugCount = Continent::where('slug', $finalSlug)->count();
                    break;
                case 'brand':
                    $slugCount = Brand::where('slug', $finalSlug)->count();
                    break;
                case 'chain':
                    $slugCount = Chain::where('slug', $finalSlug)->count();
                    break;
                case 'place':
                    $slugCount = Place::where('slug', $finalSlug)->count();
                    break;
            }

            if ($slugCount == 0) {
                return $finalSlug;
            }
            else {
                $finalSlug = $baseSlug . '-' . $tailID;
                $tailID++;
            }
        } // [END] while
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hotels = Hotel::select('chain')->groupBy('chain')->get();
        $chainFirstID = Chain::orderBy('id', 'DESC')->first();
        if (!$chainFirstID) {
            $chainFirstID = 1;
        }
        else {
            $chainFirstID = $chainFirstID->id;
        }
        foreach ($hotels as $hotel) {
            if (!empty($hotel->chain)) {
                $chainSlug = $chainFirstID . '-' . Str::slug($hotel->chain);
                $chainFirstID++;

                $chain = Chain::firstOrCreate(
                    ['slug' => $chainSlug],
                    ['name' => $hotel->chain],
                );

                $this->line('[ * ] Chain : ' . $chain->name);
            }
        }

        $hotels = Hotel::select('brand')->groupBy('brand')->get();
        $brandFirstID = Brand::orderBy('id', 'DESC')->first();
        if (!$brandFirstID) {
            $brandFirstID = 1;
        }
        else {
            $brandFirstID = $brandFirstID->id;
        }
        foreach ($hotels as $hotel) {
            if (!empty($hotel->brand)) {
                $brandSlug = $brandFirstID . '-' . Str::slug($hotel->brand);
                $brandFirstID++;

                $brand = Brand::firstOrCreate(
                    ['slug' => $brandSlug],
                    ['name' => $hotel->brand],
                );

                $this->line('[ * ] Brand : ' . $brand->name);
            }
        }

        $hotels = Hotel::select('city', 'state', 'country', 'continent')->groupBy('city', 'country')->get();
        $cityFirstID = City::orderBy('id', 'DESC')->first();
        if (!$cityFirstID) {
            $cityFirstID = 1;
        }
        else {
            $cityFirstID = $cityFirstID->id;
        }
        foreach ($hotels as $hotel) {
            if (!empty($hotel->city) && !empty($hotel->country) && !empty($hotel->continent)) {
                $citySlug = $cityFirstID . '-' . Str::slug($hotel->city);
                $cityFirstID++;

                $city = City::firstOrCreate(
                    ['slug' => $citySlug, 'state' => !empty($hotel->state) ? $hotel->state : null, 'country' => $hotel->country, 'continent' => $hotel->continent],
                    ['name' => $hotel->city]
                );
    
                $this->line('[ * ] City : ' . $city->name);
            }
        }

        $hotels = Hotel::select('state', 'country', 'continent')->groupBy('state', 'country')->get();
        $stateFirstID = State::orderBy('id', 'DESC')->first();
        if (!$stateFirstID) {
            $stateFirstID = 1;
        }
        else {
            $stateFirstID = $stateFirstID->id;
        }
        foreach ($hotels as $hotel) {
            if (!empty($hotel->state) && !empty($hotel->country) && !empty($hotel->continent)) {
                $stateSlug = $stateFirstID . '-' . Str::slug($hotel->state);
                $stateFirstID++;

                $state = State::firstOrCreate(
                    ['slug' => $stateSlug, 'country' => $hotel->country, 'continent' => $hotel->continent],
                    ['name' => $hotel->state]
                );

                $this->line('[ * ] State : ' . $state->name);
            }
        }

        $hotels = Hotel::select('country', 'country_iso_code', 'continent')->groupBy('country')->get();
        $countryFirstID = Country::orderBy('id', 'DESC')->first();
        if (!$countryFirstID) {
            $countryFirstID = 1;
        }
        else {
            $countryFirstID = $countryFirstID->id;
        }
        foreach ($hotels as $hotel) {
            if (!empty($hotel->country) && !empty($hotel->continent)) {
                $countrySlug = $countryFirstID . '-' . Str::slug($hotel->country);
                $countryFirstID++;

                $country = Country::firstOrCreate(
                    ['slug' => $countrySlug, 'continent' => $hotel->continent],
                    ['name' => $hotel->country, 'iso_code' => $hotel->country_iso_code],
                );
    
                $this->line('[ * ] Country : ' . $country->name);
            }
        }

        $hotels = Hotel::select('continent')->groupBy('continent')->get();
        $continentFirstID = Continent::orderBy('id', 'DESC')->first();
        if (!$continentFirstID) {
            $continentFirstID = 1;
        }
        else {
            $continentFirstID = $continentFirstID->id;
        }
        foreach ($hotels as $hotel) {
            if (!empty($hotel->continent)) {
                $continentSlug = $continentFirstID . '-' . Str::slug($hotel->continent);
                $continentFirstID++;

                $continent = Continent::firstOrCreate(
                    ['slug' => $continentSlug],
                    ['name' => $hotel->continent]
                );

                $this->line('[ * ] Continent : ' . $continent->name);
            }
        }
    }
}
