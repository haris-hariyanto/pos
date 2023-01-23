<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\Location\CategoryPlace;
use App\Models\Hotel\Hotel;
use App\Helpers\CacheSystem;

class CountryController extends Controller
{
    public function index($country)
    {
        $cacheKey = 'country' . $country;
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
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
                ->take(15)
                ->orderBy('user_ratings_total', 'DESC')
                ->get();
            $places = $modelPlaces->toArray();

            // Generate cache
            CacheSystem::generate($cacheKey, compact('country', 'bestHotels', 'cities', 'states', 'places'));
        }

        $categories = Category::orderBy('name', 'ASC')->whereIn('name', config('scraper.place_types_to_fetch'))->get();

        return view('main.contents.country', compact('country', 'bestHotels', 'cities', 'states', 'places', 'categories'));
    }

    public function cities($country)
    {
        $cacheKey = 'country' . $country . 'cities';
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            $modelCountry = Country::with('continent', 'cities')->where('slug', $country)->first();
            if (!$modelCountry) {
                return redirect()->route('index');
            }
            $country = $modelCountry->toArray();

            // Generate cache
            CacheSystem::generate($cacheKey, compact('country'));
        }

        return view('main.contents.country-cities', compact('country'));
    }

    public function states($country)
    {
        $cacheKey = 'country' . $country . 'states';
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            $modelCountry = Country::with('continent', 'states')->where('slug', $country)->first();
            if (!$modelCountry) {
                return redirect()->route('index');
            }
            $country = $modelCountry->toArray();

            // Generate cache
            CacheSystem::generate($cacheData, compact('country'));
        }

        return view('main.contents.country-states', compact('country'));
    }

    public function places(Request $request, $country, $category)
    {
        $currentPage = $request->query('page', 1);

        $cacheKey = 'country' . $country . 'places' . $category . 'page' . $currentPage;
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
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

            $placesModel = CategoryPlace::with('place')
                ->whereHas('place', function ($query) {
                    $query->where('hotels_nearby', '>', 0);
                })
                ->where('category_id', $modelCategory->id)
                ->where('country', $modelCountry->name)
                ->simplePaginate(24);
            $places = $placesModel->toArray();
            $places = $places['data'];

            $links = $placesModel->links('components.main.components.simple-pagination')->render();

            // Generate cache
            CacheSystem::generate($cacheKey, compact('country', 'places', 'category', 'links'));
        }

        return view('main.contents.country-places', compact('country', 'places', 'category', 'links'));
    }
}
