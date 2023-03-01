<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;
use App\Helpers\CacheSystemDB;
use App\Helpers\StructuredData;
use App\Helpers\Text;
use Illuminate\Support\Facades\Cache;

class PlaceController extends Controller
{
    public function index(Request $request, $place)
    {
        $currentPage = $request->query('page', 1);

        $queryStar = $request->query('star', null);
        $queryMinPrice = $request->query('min-price', null);
        $queryMaxPrice = $request->query('max-price', null);
        $querySortBy = $request->query('sort-by', 'popular');

        $cacheKey = 'place' . $place . 'page' . $currentPage;
        $cacheData = CacheSystemDB::get($cacheKey);

        if ($cacheData && !$request->expectsJson() && empty($queryStar) && empty($queryMinPrice) && empty($queryMaxPrice) && $querySortBy == 'popular') {
            extract($cacheData);
        }
        else {
            $modelPlace = Place::with('continent', 'country')->where('slug', $place)->first();
            if (!$modelPlace) {
                return redirect()->route('index');
            }
            $place = $modelPlace->toArray();

            $latitude = explode('.', $place['latitude']);
            $longitude = explode('.', $place['longitude']);

            if (empty($latitude[1])) {
                $latitude[1] = '0';
            }

            if (empty($longitude[1])) {
                $longitude[1] = '0';
            }

            $modelHotels = Hotel::where('latitude', 'like', $latitude[0] . '.' . substr($latitude[1], 0, 1) . '%')
                ->where('longitude', 'like', $longitude[0] . '.' . substr($longitude[1], 0, 1) . '%')
                // ->where('overview', '<>', '')
                // ->where('number_of_reviews', '>', 0)
                // ->whereNotNull('price')
                // ->whereNotNull('star_rating')
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
            $links = $modelHotels->links('components.main.components.simple-pagination')->render();
            
            $hotels = [];
            foreach ($modelHotels as $modelHotel) {
                $hotel = [];
                $hotel['hotel'] = $modelHotel->toArray();
                $hotel['hotel']['photos'] = json_decode($hotel['hotel']['photos']);

                $distanceKM = $this->distance($place['latitude'], $place['longitude'], $modelHotel->latitude, $modelHotel->longitude, 'K');
                $hotel['m_distance'] = round($distanceKM * 1000, 0);
                $hotels[] = $hotel;
            }

            $hotels = collect($hotels)->toArray();

            if ($request->expectsJson()) {
                $resultsHTML = view('main.contents.json-place', compact('place', 'hotels', 'links'))->render();
                return [
                    'results' => $resultsHTML,
                    'success' => true,
                ];
            }

            // Generate cache
            if (empty($queryStar) && empty($queryMinPrice) && empty($queryMaxPrice) && $querySortBy == 'popular') {
                $cacheTags = [];
                $cacheTags[] = '[place:' . $place['id'] . ']';
                foreach ($hotels as $hotel) {
                    $cacheTags[] = '[hotel:' . $hotel['hotel']['id'] . ']';
                }
                CacheSystemDB::generate($cacheKey, compact('place', 'hotels', 'links'), [], $cacheTags);
            }
            // [END] Generate cache
        }

        // Views counter
        $this->totalViewsHandler($place['id']);

        // Get lowest price
        $cacheKeyLowestPrice = 'place' . $place['slug'] . 'lowest-price';
        $lowestPrice = Cache::rememberForever($cacheKeyLowestPrice, function () use ($place) {
            $latitude = explode('.', $place['latitude']);
            $longitude = explode('.', $place['longitude']);

            if (count($latitude) > 1 && count($longitude) > 1) {
                $getLowestPrice = Hotel::where('latitude', 'like', $latitude[0] . '.' . substr($latitude[1], 0, 1) . '%')
                    ->where('longitude', 'like', $longitude[0] . '.' . substr($longitude[1], 0, 1) . '%')
                    ->min('price');
                
                if (is_int($getLowestPrice) && !empty($getLowestPrice)) {
                    return $getLowestPrice;
                }
                else {
                    return '-';
                }
            }
            else {
                return '-';
            }
        });
        if (!is_int($lowestPrice) || empty($lowestPrice)) {
            $lowestPrice = config('content.default_lowest_price');
        };
        $lowestPrice = Text::priceWithoutCurrency($lowestPrice);
        // [END] Get lowest price

        $structuredData = new StructuredData();
        $structuredData->breadcrumb([
            __('Home') => route('index'),
            $place['name'] => ''
        ]);

        // No hotels found
        $altPlaces = [];
        $altHotels = [];
        $altCacheKey = 'alt-place' . $place['slug'];

        if (count($hotels) == 0) {
            $altCacheData = CacheSystemDB::get($altCacheKey);
            if ($altCacheData) {
                extract($altCacheData);
            }
            else {
                $latitude = explode('.', $place['latitude']);
                $longitude = explode('.', $place['longitude']);
    
                if (count($latitude) > 1 && count($longitude) > 1) {
                    $altPlacesModel = Place::where('latitude', 'like', $latitude[0] . '.%')
                        ->where('longitude', 'like', $longitude[0] . '%')
                        ->where('id', '<>', $place['id'])
                        ->orderBy('total_views', 'DESC')
                        ->orderBy('user_ratings_total', 'DESC')
                        ->take(16)
                        ->get();
                    $altPlaces = $altPlacesModel->toArray();
                }
    
                if (count($altPlaces) < 8) {
                    $altPlacesModel = Place::where('country', $place['country']['name'])
                        ->where('id', '<>', $place['id'])
                        ->orderBy('total_views', 'DESC')
                        ->orderBy('user_ratings_total', 'DESC')
                        ->take(16)
                        ->get();
                    $altPlaces = $altPlacesModel->toArray();
                }
    
                if (count($altPlaces) > 0) {
                    $altHotelsModel = Hotel::where(function ($query) use ($altPlaces) {
                            foreach ($altPlaces as $place) {
                                $latitude = explode('.', $place['latitude']);
                                $longitude = explode('.', $place['longitude']);
    
                                $query->orWhere(function ($subQuery) use ($latitude, $longitude) {
                                    if (count($latitude) > 1 && count($longitude) > 1) {
                                        $subQuery->where('latitude', 'like', $latitude[0] . '.' . substr($latitude[1], 0, 1) . '%')
                                            ->where('longitude', 'like', $longitude[0] . '.' . substr($longitude[1], 0, 1) . '%');
                                    }
                                });
                            }
                        })
                        ->orderBy('total_views', 'DESC')
                        ->orderBy('number_of_reviews', 'DESC')
                        ->take(5)
                        ->get();
                    $altHotels = [];
                    foreach ($altHotelsModel as $altHotelModel) {
                        $altHotelModel = $altHotelModel->toArray();
                        $altHotelModel['photos'] = json_decode($altHotelModel['photos'], true);
                        $altHotels[] = $altHotelModel;
                    }
                }
    
                // Generate cache for place with no hotels found
                $altCacheTags = [];
                $altCacheTags[] = '[place:' . $place['id'] . ']';
                CacheSystemDB::generate($altCacheKey, compact('altPlaces', 'altHotels'), [
                    'altPlaces' => 'place',
                    'altHotels' => 'hotel',
                ], $altCacheTags);
                // [END] Generate cache for place with no hotels found
            }
        }
        // [END] No hotels found

        return view('main.contents.place', compact('place', 'hotels', 'links', 'currentPage', 'structuredData', 'lowestPrice', 'altPlaces', 'altHotels'));
    }

    private function totalViewsHandler($placeID)
    {
        $place = Place::where('id', $placeID)->first();
        if ($place) {
            $place->increment('total_views');
        }
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
              return $miles;
            }
    }
}