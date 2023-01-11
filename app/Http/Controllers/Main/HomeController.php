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
            $continents = Continent::with('countries')->get();
            $popularPlaces = Place::orderBy('user_ratings_total', 'DESC')->take(12)->get();
            $popularHotels = Hotel::orderBy('number_of_reviews', 'DESC')->take(12)->get();
        }

        return view('main.index', compact('continents', 'popularPlaces', 'popularHotels'));
    }
}
