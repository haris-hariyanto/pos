<?php

namespace App\Http\Controllers\Main\Misc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\City;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;

class SitemapController extends Controller
{
    private $limit = 2000;

    public function index()
    {
        $sitemaps = [];

        $limit = $this->limit;
        
        $continentsSitemap = ceil(Continent::count() / $limit);
        for ($i = 1; $i <= $continentsSitemap; $i++) {
            $sitemaps[] = route('sitemap.continents', [$i]);
        }

        $countriesSitemap = ceil(Country::count() / $limit);
        for ($i = 1; $i <= $countriesSitemap; $i++) {
            $sitemaps[] = route('sitemap.countries', [$i]);
        }

        $statesSitemap = ceil(State::count() / $limit);
        for ($i = 1; $i <= $statesSitemap; $i++) {
            $sitemaps[] = route('sitemap.states', [$i]);
        }

        $citiesSitemap = ceil(City::count() / $limit);
        for ($i = 1; $i <= $citiesSitemap; $i++) {
            $sitemaps[] = route('sitemap.cities', [$i]);
        }

        $placesSitemap = ceil(Place::count() / $limit);
        for ($i = 1; $i <= $placesSitemap; $i++) {
            $sitemaps[] = route('sitemap.places', [$i]);
        }

        $hotelsSitemap = ceil(Hotel::count() / $limit);
        for ($i = 1; $i <= $hotelsSitemap; $i++) {
            $sitemaps[] = route('sitemap.hotels', [$i]);
        }

        return response()->view('main.misc.sitemap.sitemaps-index', compact('sitemaps'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapContinents($index = 1)
    {
        $continents = Continent::select('slug')->get();

        $urls = [];
        foreach ($continents as $continent) {
            $urls[] = [
                'loc' => route('continent', [$continent->slug])
            ];
        }

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapCountries($index = 1)
    {
        $limit = $this->limit;
        $skip = ($index - 1) * $limit;

        $countries = Country::select('slug')->skip($skip)->take($limit)->get();

        $urls = [];
        foreach ($countries as $country) {
            $urls[] = [
                'loc' => route('country', [$country->slug]),
            ];
            $urls[] = [
                'loc' => route('country.cities', [$country->slug]),
            ];
            $urls[] = [
                'loc' => route('country.states', [$country->slug]),
            ];
        }

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapStates($index = 1)
    {
        $limit = $this->limit;
        $skip = ($index - 1) * $limit;

        $states = State::select('slug')->skip($skip)->take($limit)->get();

        $urls = [];
        foreach ($states as $state) {
            $urls[] = [
                'loc' => route('hotel.location', ['state', $state->slug]),
            ];
        }

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapCities($index = 1)
    {
        $limit = $this->limit;
        $skip = ($index - 1) * $limit;

        $cities = City::select('slug')->skip($skip)->take($limit)->get();

        $urls = [];
        foreach ($cities as $city) {
            $urls[] = [
                'loc' => route('hotel.location', ['city', $city->slug]),
            ];
        }

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapPlaces($index = 1)
    {
        $limit = $this->limit;
        $skip = ($index - 1) * $limit;

        $places = Place::select('slug')
            ->where('hotels_nearby', '>', 0)
            ->skip($skip)
            ->take($limit)
            ->get();

        $urls = [];
        foreach ($places as $place) {
            $urls[] = [
                'loc' => route('place', [$place->slug]),
            ];
        }

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapHotels($index = 1)
    {
        $limit = $this->limit;
        $skip = ($index - 1) * $limit;

        $hotels = Hotel::select('slug')->skip($skip)->take($limit)->get();

        $urls = [];
        foreach ($hotels as $hotel) {
            $urls[] = [
                'loc' => route('hotel', [$hotel->slug]),
            ];
        }

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }

    public function sitemapSample($index)
    {
        $urls = [
            // ['loc' => '', 'lastmod' => ''],
        ];

        return response()->view('main.misc.sitemap.sitemap', compact('urls'), 200)->header('Content-Type', 'application/xml');
    }
}
