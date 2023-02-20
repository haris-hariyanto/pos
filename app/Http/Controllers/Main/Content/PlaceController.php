<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;
use App\Helpers\CacheSystem;
use App\Helpers\StructuredData;

class PlaceController extends Controller
{
    public function index(Request $request, $place)
    {
        // Cache untuk halaman tempat di non-aktifkan agar detail hotel bisa tetap terupdate
        $currentPage = $request->query('page', 1);

        $cacheKey = 'place' . $place . 'page' . $currentPage;
        $cacheData = false; // CacheSystem::get($cacheKey);

        if ($cacheData && !$request->expectsJson()) {
            extract($cacheData);
        }
        else {
            $modelPlace = Place::with('continent', 'country')->where('slug', $place)->first();
            if (!$modelPlace) {
                return redirect()->route('index');
            }
            $place = $modelPlace->toArray();

            $queryStar = $request->query('star');
            $queryMinPrice = $request->query('min-price', null);
            $queryMaxPrice = $request->query('max-price', null);
            $querySortBy = $request->query('sort-by', 'popular');

            $latitude = explode('.', $place['latitude']);
            $longitude = explode('.', $place['longitude']);

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
            CacheSystem::generate($cacheKey, compact('place', 'hotels', 'links'));
        }

        // Views counter
        $this->totalViewsHandler($place['id']);

        $structuredData = new StructuredData();
        $structuredData->breadcrumb([
            __('Home') => route('index'),
            $place['name'] => ''
        ]);

        return view('main.contents.place', compact('place', 'hotels', 'links', 'currentPage', 'structuredData'));
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