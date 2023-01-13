<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Chain;
use App\Models\Hotel\Brand;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\City;
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

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /*
        $hotels = Hotel::select('chain')->distinct()->get();
        foreach ($hotels as $hotel) {
            if (!empty($hotel->chain)) {
                $chain = Chain::firstOrCreate(
                    ['slug' => Str::slug($hotel->chain)],
                    ['name' => $hotel->chain]
                );
    
                Hotel::where('chain', $chain->name)->update([
                    'chain_id' => $chain->id,
                ]);
    
                $this->line('[ * ] Chain : ' . $chain->name);
            }
        }

        $hotels = Hotel::select('brand')->distinct()->get();
        foreach ($hotels as $hotel) {
            if (!empty($hotel->brand)) {
                $brand = Brand::firstOrCreate(
                    ['slug' => Str::slug($hotel->brand)],
                    ['name' => $hotel->brand]
                );

                Hotel::where('brand', $brand->name)->update([
                    'brand_id' => $brand->id,
                ]);

                $this->line('[ * ] Brand : ' . $brand->name);
            }
        }
        */

        $hotels = Hotel::select('city', 'state', 'country', 'continent')->distinct()->get();
        foreach ($hotels as $hotel) {
            $city = City::firstOrCreate(
                ['slug' => Str::slug($hotel->city), 'state' => !empty($hotel->state) ? $hotel->state : null, 'country' => $hotel->country, 'continent' => $hotel->continent],
                ['name' => $hotel->city]
            );

            Hotel::where('city', $city->name)->update([
                'city_id' => $city->id,
            ]);

            $this->line('[ * ] City : ' . $city->name);
        }

        $hotels = Hotel::select('state', 'country', 'continent')->distinct()->get();
        foreach ($hotels as $hotel) {
            if (!empty($hotel->state)) {
                $state = State::firstOrCreate(
                    ['slug' => Str::slug($hotel->state), 'country' => $hotel->country, 'continent' => $hotel->continent],
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
        foreach ($hotels as $hotel) {
            $country = Country::firstOrCreate(
                ['slug' => Str::slug($hotel->country), 'continent' => $hotel->continent],
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

        $hotels = Hotel::select('continent')->distinct()->get();
        foreach ($hotels as $hotel) {
            if (!empty($hotel->continent)) {
                $continent = Continent::firstOrCreate(
                    ['slug' => Str::slug($hotel->continent)],
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

        $hotels = Hotel::select('chain', 'brand')->get();
        foreach ($hotels as $hotel) {
            if (!empty($hotel->chain)) {
                $chain = Chain::firstOrCreate(
                    ['slug' => Str::slug($hotel->chain)],
                    ['name' => $hotel->chain]
                );

                Hotel::where('chain', $chain->name)->update([
                    'chain_id' => $chain->id,
                ]);

                $this->line('[ * ] Chain : ' . $chain->name);
            }

            if (!empty($hotel->brand)) {
                $brand = Brand::firstOrCreate(
                    ['slug' => Str::slug($hotel->brand)],
                    ['name' => $hotel->brand]
                );

                Hotel::where('brand', $brand->name)->update([
                    'brand_id' => $brand->id,
                ]);

                $this->line('[ * ] Brand : ' . $brand->name);
            }
        }
    }
}
