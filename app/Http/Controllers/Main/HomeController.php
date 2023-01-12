<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Continent;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;

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
                
                $continent['countries'] = [];
                foreach ($modelContinent->countries()->take(8)->get() as $country) {
                    $continent['countries'][] = [
                        'name' => $country->name,
                    ];
                }

                $continents[] = $continent;
            }

            // Popular places
            $modelPopularPlaces = Place::orderBy('user_ratings_total', 'DESC')->take(12)->get();
            $popularPlaces = [];
            foreach ($modelPopularPlaces as $modelPopularPlace) {
                $popularPlaces[] = [
                    'name' => $modelPopularPlace->name,
                    'country' => $modelPopularPlace->country,
                ];
            }

            

            $popularHotels = Hotel::orderBy('number_of_reviews', 'DESC')->take(12)->get();
        }

        return view('main.index', compact('continents', 'popularPlaces', 'popularHotels'));
    }
}
