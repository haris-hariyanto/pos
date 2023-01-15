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
        $hotels = Hotel::select('chain')->distinct()->get();
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

                Hotel::where('chain', $chain->name)->update([
                    'chain_id' => $chain->id,
                ]);

                $this->line('[ * ] Chain : ' . $chain->name);
            }
        }

        $hotels = Hotel::select('brand')->distinct()->get();
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

                Hotel::where('brand', $brand->name)->update([
                    'brand_id' => $brand->id,
                ]);

                $this->line('[ * ] Brand : ' . $brand->name);
            }
        }

        $hotels = Hotel::select('city', 'state', 'country', 'continent')->distinct()->get();
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
    
                Hotel::where('city', $city->name)->update([
                    'city_id' => $city->id,
                ]);
    
                $this->line('[ * ] City : ' . $city->name);
            }
        }

        $hotels = Hotel::select('state', 'country', 'continent')->distinct()->get();
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

                Hotel::where('state', $state->name)->update([
                    'state_id' => $state->id,
                ]);

                City::where('state', $state->name)->update([
                    'state_id' => $state->id,
                ]);

                $this->line('[ * ] State : ' . $state->name);
            }
        }

        $hotels = Hotel::select('country', 'country_iso_code', 'continent')->distinct()->get();
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
    
                Hotel::where('country', $country->name)->update([
                    'country_id' => $country->id,
                ]);
    
                City::where('country', $country->name)->update([
                    'country_id' => $country->id,
                ]);
    
                State::where('country', $country->name)->update([
                    'country_id' => $country->id,
                ]);
    
                $this->line('[ * ] Country : ' . $country->name);
            }
        }

        $hotels = Hotel::select('continent')->distinct()->get();
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

                City::where('continent', $continent->name)->update([
                    'continent_id' => $continent->id,
                ]);

                Hotel::where('continent', $continent->name)->update([
                    'continent_id' => $continent->id,
                ]);

                State::where('continent', $continent->name)->update([
                    'continent_id' => $continent->id,
                ]);

                Country::where('continent', $continent->name)->update([
                    'continent_id' => $continent->id,
                ]);

                $this->line('[ * ] Continent : ' . $continent->name);
            }
        }

        // Insert all cities, states, countries, and continents into places table
        /*
        $cities = City::get();
        foreach ($cities as $city) {
            $hotelsInThisCity = Hotel::select('id')
                ->where('city_id', $city->id)
                ->get();

            $placeSlug = $this->createUniqueSlug($city->name, 'place');
            $place = Place::create([
                'slug' => $placeSlug,
                'name' => $city->name,
                'type' => 'CITY',
                'latitude' => '0',
                'longitude' => '0',
                'state' => $city->state,
                'state_id' => $city->state_id,
                'country' => $city->country,
                'country_id' => $city->country_id,
                'continent' => $city->continent,
                'continent_id' => $city->continent_id,
                'is_hotels_scraped' => 'Y',
                'hotels_nearby' => count($hotelsInThisCity),
            ]);

            foreach ($hotelsInThisCity as $hotelInThisCity) {
                $this->line('[ * ] Menambahkan daftar hotel ke daftar kota');
                HotelPlace::create([
                    'hotel_id' => $hotelInThisCity->id,
                    'place_id' => $place->id,
                    'm_distance' => 0,
                ]);
            }
        }

        $states = State::get();
        foreach ($states as $state) {
            $hotelsInThisState = Hotel::select('id')
                ->where('state_id', $state->id)
                ->get();
            
            $placeSlug = $this->createUniqueSlug($state->name, 'place');
            $place = Place::create([
                'slug' => $placeSlug,
                'name' => $state->name,
                'type' => 'STATE',
                'latitude' => '0',
                'longitude' => '0',
                'country' => $state->country,
                'country_id' => $state->country_id,
                'continent' => $state->continent,
                'continent_id' => $state->continent_id,
                'is_hotels_scraped' => 'Y',
                'hotels_nearby' => count($hotelsInThisState),
            ]);

            foreach ($hotelsInThisState as $hotelInThisState) {
                $this->line('[ * ] Menambahkan daftar hotel ke daftar state');
                HotelPlace::create([
                    'hotel_id' => $hotelInThisState->id,
                    'place_id' => $place->id,
                    'm_distance' => 0,
                ]);
            }
        }

        $countries = Country::get();
        foreach ($countries as $country) {
            $hotelsInThisCountry = Hotel::select('id')
                ->where('country_id', $country->id)
                ->get();
            
            $placeSlug = $this->createUniqueSlug($country->name, 'place');
            $place = Place::create([
                'slug' => $placeSlug,
                'name' => $country->name,
                'type' => 'COUNTRY',
                'latitude' => '0',
                'longitude' => '0',
                'continent' => $country->continent,
                'continent_id' => $country->continent_id,
                'is_hotels_scraped' => 'Y',
                'hotels_nearby' => count($hotelsInThisCountry),
            ]);

            foreach ($hotelsInThisCountry as $hotelInThisCountry) {
                $this->line('[ * ] Menambahkan daftar hotel ke daftar negara');
                HotelPlace::create([
                    'hotel_id' => $hotelInThisCountry->id,
                    'place_id' => $place->id,
                    'm_distance' => 0,
                ]);
            }
        }
        */
    }
}
