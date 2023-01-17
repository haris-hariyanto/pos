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
            $modelCountry = Country::with('continent')->where('slug', $country)->first();
            if (!$modelCountry) {
                return redirect()->route('index');
            }
            $country = $modelCountry->toArray();

            $modelBestHotels = Hotel::where('country', $modelCountry->name)->where('continent', $modelCountry->continent)->orderBy('number_of_reviews')->take(12)->get();
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

            $modelCities = City::where('country', $modelCountry->name)->where('continent', $modelCountry->continent)->orderBy('name', 'ASC')->limit(24)->get();
            $cities = $modelCities->toArray();

            $modelStates = State::where('country', $modelCountry->name)->where('continent', $modelCountry->continent)->orderBy('name', 'ASC')->limit(24)->get();
            $states = $modelStates->toArray();

            $modelPlaces = Place::where('country', $modelCountry->name)->where('continent', $modelCountry->continent)
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

    public function cities($country)
    {
        $isCachedData = false;
        if ($isCachedData) {

        }
        else {
            $modelCountry = Country::with('continent', 'cities')->where('slug', $country)->first();
            if (!$modelCountry) {
                return redirect()->route('index');
            }
            $country = $modelCountry->toArray();
        }

        return view('main.contents.country-cities', compact('country'));
    }

    public function states($country)
    {
        $isCachedData = false;
        if ($isCachedData) {

        }
        else {
            $modelCountry = Country::with('continent', 'states')->where('slug', $country)->first();
            if (!$modelCountry) {
                return redirect()->route('index');
            }
            $country = $modelCountry->toArray();
        }

        return view('main.contents.country-states', compact('country'));
    }

    public function places($country, $category)
    {
        $isCachedData = false;
        if ($isCachedData) {

        }
        else {
            $modelCountry = Country::with('continent')->where('slug', $country)->first();
            $modelCategory = Category::where('slug', $category)->first();
            if (!$modelCountry || !$modelCategory) {
                return redirect()->route('index');
            }
            $country = $modelCountry->toArray();
            $category = $modelCategory->toArray();
            $category['name'] = __(ucwords(str_replace('_', ' ', $category['name'])));
            $modelPlaces = $modelCountry
                ->places()
                ->where('category_id', $modelCategory->id)
                ->where('hotels_nearby', '>', 0)
                ->get();
            $places = $modelPlaces->toArray();
        }

        return view('main.contents.country-places', compact('country', 'places', 'category'));
    }
}
