<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Category;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;
use App\Helpers\CacheSystemDB;
use Illuminate\Support\Facades\Http;

class GenerateCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '_content:cache';

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
        $this->line('Membuat Cache');

        $route = route('index');
        $this->info('[ * ] Membuat cache : ' . $route);
        Http::retry(5, 1000)->get($route);

        $continents = Continent::get();
        foreach ($continents as $continent) {
            $route = route('continent', [$continent->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            Http::retry(5, 1000)->get($route);
        }

        $countries = Country::get();
        $categories = Category::orderBy('name', 'ASC')->whereIn('name', config('scraper.place_types_to_fetch'))->get();
        foreach ($countries as $country) {
            $routes = ['country', 'country.cities', 'country.states'];
            foreach ($routes as $route) {
                $route = route($route, [$country->slug]);
                $this->info('[ * ] Membuat cache : ' . $route);
                Http::retry(5, 1000)->get($route);
            }

            foreach ($categories as $category) {
                $route = route('country.places', [$country->slug, $category->slug]);
                $this->info('[ * ] Membuat cache : ' . $route);
                Http::retry(5, 1000)->get($route);
            }
        }

        $places = Place::get();
        foreach ($places as $place) {
            $route = route('place', [$place->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            Http::retry(5, 1000)->get($route);
        }

        $cities = City::get();
        foreach ($cities as $city) {
            $route = route('hotel.location', ['city', $city->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            Http::retry(5, 1000)->get($route);
        }

        $states = State::get();
        foreach ($states as $state) {
            $route = route('hotel.location', ['state', $state->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            Http::retry(5, 1000)->get($route);
        }

        $hotels = Hotel::get();
        foreach ($hotels as $hotel) {
            if (!CacheSystemDB::get('hotel' . $hotel->slug)) {
                $route = route('hotel', [$hotel->slug]);
                $this->info('[ * ] Membuat cache : ' . $route);
                dispatch(function () use ($route) {
                    Http::retry(5, 1000)->get($route);
                });
            }
        }
    }
}
