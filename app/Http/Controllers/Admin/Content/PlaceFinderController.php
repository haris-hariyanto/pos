<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\Location\CategoryPlace;
use App\Helpers\GooglePlaces;
use Illuminate\Support\Str;

class PlaceFinderController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Places') => route('admin.places.index'),
            __('Google Places') => '',
        ];

        $countries = Country::get();

        $placesList = [];

        // Find places
        $placeName = $request->query('place');
        $countryName = $request->query('country');
        $countryModel = Country::where('name', $countryName)->first();
        
        if (!empty($placeName) && !empty($countryName) && $countryModel) {
            $googlePlaces = new GooglePlaces();
            $getPlaces = $googlePlaces->searchPlaces($placeName, $countryModel->iso_code);
            
            if ($getPlaces['success']) {
                $placesList = $getPlaces['results'];
            }
        }
        // [END] Find places

        if (!empty(config('scraper.places_api'))) {
            return view('admin.content.places.find', compact('breadcrumb', 'countries', 'placesList'));
        }
        else {
            return view('admin.content.places.find-not-available', compact('breadcrumb'));
        }
    }

    public function store(Request $request)
    {
        if (empty(config('scraper.places_api'))) {
            abort(500);
        }

        $request->validate([
            'country' => ['required', 'exists:countries,name'],
            'places' => ['required', 'array', 'min:1'],
        ]);

        $country = Country::where('name', $request->country)->first();
        $places = $request->places;
        foreach ($places as $place) {
            $place = json_decode($place, true);

            $placeExists = Place::where('gmaps_id', $place['id'])->first();
            if ($placeExists) {
                $placeExists->update([
                    'name' => $place['name'],
                ]);
            }
            else {
                $placeSlug = $this->createUniqueSlug($place['name']);

                $additionalData = [];
                $additionalData['viewport'] = $place['viewport'];

                $placeInstance = Place::create([
                    'slug' => $placeSlug,
                    'name' => $place['name'],
                    'type' => 'PLACE',
                    'address' => $place['address'],
                    'longitude' => $place['longitude'],
                    'latitude' => $place['latitude'],
                    'country' => $country->name,
                    'continent' => $country->continent,
                    'gmaps_id' => $place['id'],
                    'additional_data' => json_encode($additionalData),
                    'user_ratings_total' => $place['user_ratings_total'],
                ]);

                foreach ($place['types'] as $category) {
                    $category = Category::firstOrCreate([
                        'name' => $category,
                        'slug' => Str::slug($category),
                    ]);

                    CategoryPlace::firstOrCreate([
                        'category_id' => $category->id,
                        'place_id' => $placeInstance->id,
                        'country' => $country->name,
                        'continent' => $country->continent,
                    ]);
                } // [END] foreach
            }
        }

        return redirect()->route('admin.places.index')->with('success', __('Places has been added!'));
    }

    private function createUniqueSlug($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $tailID = 1;
        $loop = true;
        while ($loop) {
            $slugCount = Place::where('slug', $slug)->count();
            if ($slugCount == 0) {
                $loop = false;
                break;
            }
            else {
                $slug = $baseSlug . '-' . $tailID;
                $tailID++;
            }
        }

        if (empty(trim($slug))) {
            $lastRecord = Place::orderBy('id', 'desc')->first();
            $slug = $lastRecord->id + 1;
        }

        return $slug;
    }
}
