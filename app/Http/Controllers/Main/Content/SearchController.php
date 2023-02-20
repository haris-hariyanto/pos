<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Location\City;
use App\Models\Hotel\Hotel;
use Illuminate\Support\Facades\DB;
use App\Helpers\Settings;
use App\Helpers\GooglePlaces;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $searchMode = $request->query('mode', 'findPlaces');
        $searchQuery = $request->query('q');

        if (!empty($searchQuery)) {
            if ($searchMode == 'findHotels') {
                return redirect()->route('search.hotels', ['q' => $searchQuery]);
            }
            else {
                return redirect()->route('search.places', ['q' => $searchQuery]);
            }
        }
        else {
            return redirect()->route('index');
        }
    }

    public function searchHotels(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return redirect()->route('index');
        }

        $querySearch = $query;
        $querySearch = preg_replace('/\s+/', ' ', $querySearch);
        $querySearch = explode(' ', $querySearch);

        $queryStar = $request->query('star');
        $queryMinPrice = $request->query('min-price', null);
        $queryMaxPrice = $request->query('max-price', null);
        $querySortBy = $request->query('sort-by', 'popular');

        $results = Hotel::where(function ($query) use ($querySearch) {
                $query->where(function ($subQuery) use ($querySearch) {
                    foreach ($querySearch as $querySearchPart) {
                        $subQuery->where('name', 'like', '%' . $querySearchPart . '%');
                    }
                })
                ->orWhere('city', implode(' ', $querySearch))
                ->orWhere('state', implode(' ', $querySearch));
            })
            ->when($queryStar, function ($query, $queryStar) {
                $queryStar = explode(',', $queryStar);
                $query->where(function ($subQuery) use ($queryStar) {
                    foreach ($queryStar as $star) {
                        if ($star == 'unrated') {
                            $subQuery->orWhereNull('star_rating');
                        }
                        else {
                            if (!empty($star)) {
                                $subQuery->orWhere('star_rating', 'like', $star . '%');
                            }
                        }
                    }
                });
            })
            ->when($queryMinPrice, function ($query, $queryMinPrice) {
                if (is_numeric($queryMinPrice) && !empty($queryMinPrice)) {
                    $query->where('price', '>=', $queryMinPrice);
                }
            })
            ->when($queryMaxPrice, function ($query, $queryMaxPrice) {
                if (is_numeric($queryMaxPrice) && !empty($queryMaxPrice)) {
                    $query->where('price', '<=', $queryMaxPrice);
                }
            })
            ->when($querySortBy == 'popular', function ($query) {
                $query->orderBy('total_views', 'DESC')
                    ->orderBy('number_of_reviews', 'DESC');
            })
            ->when($querySortBy == 'lowest-price', function ($query) {
                $query->orderBy('price', 'ASC');
            })
            ->when($querySortBy == 'highest-price', function ($query) {
                $query->orderBy('price', 'DESC');
            })
            ->when($queryMinPrice || $queryMaxPrice || $querySortBy == 'lowest-price' || $querySortBy == 'highest-price', function ($query) {
                $query->whereNotNull('price');
            })
            ->simplePaginate(25)
            ->withQueryString();
        
        $resultsArray = [];
        foreach ($results as $result) {
            $result = $result->toArray();
            $result['photos'] = json_decode($result['photos'], true);
            $resultsArray[] = $result;
        }

        if ($request->expectsJson()) {
            $resultsHTML = view('main.contents.json-search-hotels', compact('results', 'resultsArray'))->render();
            return [
                'results' => $resultsHTML,
                'success' => true,
            ];
        }
        
        return view('main.contents.search-hotels', compact('query', 'results', 'resultsArray'));
    }

    public function searchPlaces(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return redirect()->route('index');
        }

        $querySearch = $query;
        $querySearch = preg_replace('/\s+/', ' ', $querySearch);
        $querySearch = explode(' ', $querySearch);

        // $results = Place::where('hotels_nearby', '>', 0)
        $results = Place::where(function ($query) use ($querySearch) {
                $query->where(function ($subQuery) use ($querySearch) {
                    foreach ($querySearch as $querySearchPart) {
                        $subQuery->where('name', 'like', '%' . $querySearchPart . '%');
                    }
                })->orWhere(function ($subQuery) use ($querySearch) {
                    foreach ($querySearch as $querySearchPart) {
                        $subQuery->where('address', 'like', '%' . $querySearchPart . '%');
                    }
                })->orWhere('country', 'like', '%' . implode(' ', $querySearch) . '%');
            })
            // ->orderBy('hotels_nearby', 'DESC')
            ->orderBy('user_ratings_total', 'DESC')
            ->simplePaginate(24)
            ->withQueryString();
        
        $isSearchFromAPI = false;
        if (count($results) < 1) {
            $googlePlaces = new GooglePlaces();
            $getPlaces = $googlePlaces->searchPlaces($query);

            if ($getPlaces['success']) {
                $isSearchFromAPI = true;

                $results = [];
                foreach ($getPlaces['results'] as $place) {
                    $results[] = [
                        'name' => $place['name'],
                        'address' => $place['address'],
                        'url' => route('new-place', [$place['id']]),
                    ];
                } // [END] foreach
            }
        }

        return view('main.contents.search-places', compact('query', 'results', 'isSearchFromAPI'));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->query('q');
        if ($query) {
            $places = Place::with('categories')
                ->where('name', 'LIKE', '%' . $query . '%')
                ->take(5)
                ->get();
            $places = $places->map(function ($item) {
                return [
                    'name' => $item->name,
                    'tag' => $item->categories()->first() ? __(ucwords(str_replace('_', ' ', $item->categories()->first()->name))) : __('Other'),
                    'route' => route('place', [$item->slug]),
                ];
            })->toArray();

            $autocompleteResults = $places;

            if (count($autocompleteResults) < 5) {
                $cities = City::where('name', 'LIKE', $query . '%')
                    ->take(5)
                    ->get();
                $cities = $cities->map(function ($item) {
                    return [
                        'name' => $item->name,
                        'tag' => __('City'),
                        'route' => route('hotel.location', [config('content.location_term_city'), $item->slug]),
                    ];
                })->toArray();

                $autocompleteResults = array_merge($autocompleteResults, $cities);
            }

            $searchAPIEnabled = Settings::get('searchsettings__enabled', 'N');
            if (count($autocompleteResults) < 5 && $searchAPIEnabled == 'Y') {
                $googlePlaces = new GooglePlaces();
                $getPlaces = $googlePlaces->searchPlaces($query);

                if ($getPlaces['success']) {
                    $placeList = $getPlaces['results'];

                    $APIPlacesResults = [];
                    foreach ($placeList as $place) {
                        if ($place['types'][0] != 'lodging') {
                            $APIPlacesResults[] = [
                                'name' => $place['name'],
                                'tag' => __(ucwords(str_replace('_', ' ', $place['types'][0]))),
                                'route' => route('new-place', [$place['id']]),
                            ];
                        }
                    }

                    $autocompleteResults = array_merge($autocompleteResults, $APIPlacesResults);
                }
            }

            return [
                'results' => $autocompleteResults,
                'success' => true,
            ];
        }
        else {
            return [
                'results' => [],
                'success' => true,
            ];
        }
    }

    public function newPlace($placeID)
    {
        $searchAPIEnabled = Settings::get('searchsettings__enabled', 'N');
        if ($searchAPIEnabled == 'N') {
            return redirect()->route('index');
        }

        $placeExists = Place::where('gmaps_id', $placeID)->first();
        if ($placeExists) {
            return redirect()->route('place', [$placeExists->slug]);
        }

        $googlePlaces = new GooglePlaces();
        $getPlace = $googlePlaces->details($placeID);
        if ($getPlace['success']) {
            $place = $getPlace['result'];

            $placeSlug = $this->createUniqueSlug($place['name']);
            
            $additionalData = [];
            $additionalData['viewport'] = $place['geometry']['viewport'];
            
            $placeInstance = Place::create([
                'slug' => $placeSlug,
                'name' => $place['name'],
                'type' => 'PLACE',
                'address' => !empty($place['formatted_address']) ? $place['formatted_address'] : null,
                'longitude' => $place['geometry']['location']['lng'],
                'latitude' => $place['geometry']['location']['lat'],
                'gmaps_id' => $placeID,
                'additional_data' => json_encode($additionalData),
                'user_ratings_total' => !empty($place['user_ratings_total']) ? $place['user_ratings_total'] : 0,
            ]);

            return redirect()->route('place', [$placeInstance->slug]);
        }
        else {
            return redirect()->route('index');
        }
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
