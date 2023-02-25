<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\Location\CategoryPlace;
use App\Models\Location\City;
use App\Helpers\CacheSystemDB;

class ContinentController extends Controller
{
    public function index($continent)
    {
        $cacheKey = 'continent' . $continent;
        $cacheData = CacheSystemDB::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            $modelContinent = Continent::with('countries')->where('slug', $continent)->first();
            if (!$modelContinent) {
                return redirect()->route('index');
            }
            $continent = $modelContinent->toArray();

            $categories = Category::with('places')
                ->whereIn('name', config('scraper.place_types_to_fetch'))
                ->orderBy('name', 'ASC')
                ->get();
            $places = [];
            foreach ($categories as $category) {
                $modelPlaces = $category->places()
                    ->where('places.continent', $modelContinent->name)
                    ->orderBy('total_views', 'DESC')
                    ->orderBy('user_ratings_total', 'DESC')
                    ->take(15)
                    ->get();
                $places[$category->name] = $modelPlaces->toArray();
            }

            $modelCities = City::where('continent', $modelContinent->name)
                ->orderBy('total_views', 'DESC')
                ->take(24)
                ->get();
            $cities = $modelCities->toArray();

            // Generate cache
            $cacheTags = [];
            $cacheTags[] = '[continent:' . $continent['id'] . ']';
            foreach ($continent['countries'] as $country) {
                $cacheTags[] = '[country:' . $country['id'] . ']';
            }
            foreach ($categories as $category) {
                $cacheTags[] = '[category:' . $category->id . ']';
            }
            foreach ($places as $placesList) {
                foreach ($placesList as $place) {
                    $cacheTags[] = '[place:' . $place['id'] . ']';
                }
            }

            CacheSystemDB::generate($cacheKey, compact('continent', 'places', 'cities'), [
                'cities' => 'city',
            ], $cacheTags);
            // [END] Generate cache
        }

        return view('main.contents.continent', compact('continent', 'places', 'cities'));
    }
}
