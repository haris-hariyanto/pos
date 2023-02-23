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

class GenerateCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:cache';

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
        $this->line('Membuat Cache');

        $route = route('index');
        $this->info('[ * ] Membuat cache : ' . $route);
        $request = Request::create($route, 'GET');
        $response = app()->handle($request);

        $continents = Continent::get();
        foreach ($continents as $continent) {
            $route = route('continent', [$continent->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            $request = Request::create($route, 'GET');
            $response = app()->handle($request);
        }

        $countries = Country::get();
        $categories = Category::orderBy('name', 'ASC')->whereIn('name', config('scraper.place_types_to_fetch'))->get();
        foreach ($countries as $country) {
            $routes = ['country', 'country.cities', 'country.states'];
            foreach ($routes as $route) {
                $route = route($route, [$country->slug]);
                $this->info('[ * ] Membuat cache : ' . $route);
                $request = Request::create($route, 'GET');
                $response = app()->handle($request);
            }

            foreach ($categories as $category) {
                $route = route('country.places', [$country->slug, $category->slug]);
                $this->info('[ * ] Membuat cache : ' . $route);
                $request = Request::create($route, 'GET');
                $response = app()->handle($request);
            }
        }

        $places = Place::get();
        foreach ($places as $place) {
            $route = route('place', [$place->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            $request = Request::create($route, 'GET');
            $response = app()->handle($request);
        }
        */

        $hotels = Hotel::get();
        foreach ($hotels as $hotel) {
            $route = route('hotel', [$hotel->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            file_get_contents($route);
            // $request = Request::create($route, 'GET');
            // $response = app()->handle($request);
        }

        $cities = City::get();
        foreach ($cities as $city) {
            $route = route('hotel.location', ['city', $city->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            file_get_contents($route);
            // $request = Request::create($route, 'GET');
            // $response = app()->handle($request);
        }

        $states = State::get();
        foreach ($states as $state) {
            $route = route('hotel.location', ['state', $state->slug]);
            $this->info('[ * ] Membuat cache : ' . $route);
            file_get_contents($route);
            // $request = Request::create($route, 'GET');
            // $response = app()->handle($request);
        }
    }
}
