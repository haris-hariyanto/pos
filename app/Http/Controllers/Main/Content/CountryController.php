<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\Hotel\Hotel;

class CountryController extends Controller
{
    public function index($country)
    {
        $isCachedData = false;
        if ($isCachedData) {

        }
        else {
            $modelCountry = Country::where('slug', $country)->first();
            if (!$modelCountry) {
                return redirect()->route('index');
            }
            $country = $modelCountry->toArray();

            $modelBestHotels = Hotel::where('country_id', $modelCountry->id)->orderBy('number_of_reviews')->take(12)->get();
            $bestHotels = [];
            foreach ($modelBestHotels as $modelBestHotel) {
                $images = json_decode($modelBestHotel->photos, true);
                $bestHotels[] = [
                    'slug' => $modelBestHotel->slug,
                    'name' => $modelBestHotel->name,
                    'images' => $images,
                    'address_line_1' => $modelBestHotel->address_line_1,
                    'city' => $modelBestHotel->city,
                    'star_rating' => $modelBestHotel->star_rating,
                ];
            }

            $modelCities = City::where('country_id', $modelCountry->id)->orderBy('name', 'ASC')->limit(24)->get();
            $cities = $modelCities->toArray();

            $modelStates = State::where('country_id', $modelCountry->id)->orderBy('name', 'ASC')->limit(24)->get();
            $states = $modelStates->toArray();

            $modelPlaces = Place::where('country_id', $modelCountry->id)
                ->where('type', 'PLACE')
                ->where('is_hotels_scraped', 'Y')
                ->where('hotels_nearby', '>', 0)
                ->take(20)
                ->orderBy('user_ratings_total', 'DESC')
                ->get();
            $places = $modelPlaces->toArray();

        }

        $categories = Category::orderBy('name', 'ASC')->get();

        return view('main.contents.country', compact('country', 'bestHotels', 'cities', 'states', 'places', 'categories'));
    }
}
