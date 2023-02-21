<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Models\Location\Place;
use App\Models\Location\City;
use App\Models\Hotel\Hotel;
use App\Models\MetaData;
use App\Helpers\CacheSystemDB;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $cacheKey = 'homepage';
        $cacheData = CacheSystemDB::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            // Continents and countries
            $modelContinents = Continent::with('countries')->get();
            $continents = [];
            foreach ($modelContinents as $modelContinent) {
                $continent = [];
                $continent['id'] = $modelContinent->id;
                $continent['name'] = $modelContinent->name;
                $continent['slug'] = $modelContinent->slug;
                
                $continent['countries'] = [];
                foreach ($modelContinent->countries()->take(8)->get() as $country) {
                    $continent['countries'][] = [
                        'id' => $country->id,
                        'name' => $country->name,
                        'slug' => $country->slug,
                    ];
                }

                $continents[] = $continent;
            }

            // Popular places
            $modelPopularPlaces = Place::orderBy('total_views', 'DESC')->take(12)->get();
            $popularPlaces = $modelPopularPlaces->toArray();

            // Popular cities
            $modelPopularCities = City::orderBy('total_views', 'DESC')->take(12)->get();
            $popularCities = $modelPopularCities->toArray();

            // Popular hotels
            $modelPopularHotels = Hotel::orderBy('weekly_views', 'DESC')
                ->orderBy('number_of_reviews', 'DESC')
                ->take(12)
                ->get();
            $popularHotels = $modelPopularHotels->map(function ($modelPopularHotel) {
                $popularHotel = $modelPopularHotel->toArray();
                $popularHotel['photos'] = json_decode($popularHotel['photos'], true);
                return $popularHotel;
            })->toArray();

            // Get cover images for each continents
            $homeCoverImages = MetaData::where('key', 'home_cover_images')->first();
            if ($homeCoverImages) {
                $homeCoverImages = $homeCoverImages->value;
                $homeCoverImages = json_decode($homeCoverImages, true);
            }

            // Generate cache
            $cacheTags = [];
            $cacheTags[] = '[coverImages]';
            foreach ($continents as $continent) {
                foreach ($continent['countries'] as $country) {
                    $cacheTags[] = '[country:' . $country['id'] . ']';
                }
            }
            CacheSystemDB::generate($cacheKey, compact('continents', 'popularPlaces', 'popularHotels', 'popularCities', 'homeCoverImages'), [
                'continents' => 'continent',
                'popularPlaces' => 'place',
                'popularHotels' => 'hotel',
                'popularCities' => 'city',
            ], $cacheTags, 7);
            // [END] Generate cache
        }

        return view('main.index', compact('continents', 'popularPlaces', 'popularHotels', 'popularCities', 'homeCoverImages'));
    }
}
