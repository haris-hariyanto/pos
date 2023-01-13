<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\State;
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
                    'name' => $modelBestHotel->name,
                    'images' => $images,
                    'address_line_1' => $modelBestHotel->address_line_1,
                    'city' => $modelBestHotel->city,
                    'star_rating' => $modelBestHotel->star_rating,
                ];
            }

            $modelCities = City::where('country_id', $modelCountry->id)->orderBy('name', 'ASC')->get();
            $cities = $modelCities->toArray();

            $modelStates = State::where('country_id', $modelCountry->id)->orderBy('name', 'ASC')->get();
            $states = $modelStates->toArray();
        }

        return view('main.contents.country', compact('country', 'bestHotels', 'cities', 'states'));
    }
}
