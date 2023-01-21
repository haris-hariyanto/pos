<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;
use App\Models\MetaData;

class HomeController extends Controller
{
    public function index()
    {
        $isCachedData = false;
        if ($isCachedData) {

        }
        else {
            // Continents and countries
            $modelContinents = Continent::with('countries')->get();
            $continents = [];
            foreach ($modelContinents as $modelContinent) {
                $continent = [];
                $continent['name'] = $modelContinent->name;
                $continent['slug'] = $modelContinent->slug;
                
                $continent['countries'] = [];
                foreach ($modelContinent->countries()->take(8)->get() as $country) {
                    $continent['countries'][] = [
                        'name' => $country->name,
                        'slug' => $country->slug,
                    ];
                }

                $continents[] = $continent;
            }

            // Popular places
            $modelPopularPlaces = Place::orderBy('user_ratings_total', 'DESC')->where('hotels_nearby', '>', 0)->take(12)->get();
            $popularPlaces = $modelPopularPlaces->toArray();

            // Popular hotels
            $modelPopularHotels = Hotel::orderBy('number_of_reviews', 'DESC')->take(12)->get();
            $popularHotels = $modelPopularHotels->toArray();

            // Get cover images for each continents
            $homeCoverImages = MetaData::where('key', 'home_cover_images')->first();
            if ($homeCoverImages) {
                $homeCoverImages = $homeCoverImages->value;
                $homeCoverImages = json_decode($homeCoverImages, true);
            }
        }

        return view('main.index', compact('continents', 'popularPlaces', 'popularHotels', 'homeCoverImages'));
    }
}
