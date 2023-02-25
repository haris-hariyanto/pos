<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Hotel\Hotel;
use App\Helpers\CacheSystemDB;

class LocationController extends Controller
{
    public function index(Request $request, $type, $location)
    {
        $currentPage = $request->query('page', 1);

        $queryStar = $request->query('star', null);
        $queryMinPrice = $request->query('min-price', null);
        $queryMaxPrice = $request->query('max-price', null);
        $querySortBy = $request->query('sort-by', 'popular');

        $cacheKey = 'location' . $type . $location . 'page' . $currentPage;
        $cacheData = CacheSystemDB::get($cacheKey);

        if ($cacheData && !$request->expectsJson() && empty($queryStar) && empty($queryMinPrice) && empty($queryMaxPrice) && $querySortBy == 'popular') {
            extract($cacheData);
        }
        else {
            if (!in_array($type, ['city', 'state', config('content.location_term_city'), config('content.location_term_state')])) {
                return redirect()->route('index');
            }
            
            if ($type == config('content.location_term_city') || $type == 'city') {
                $modelCity = City::with('continent', 'country')->where('slug', $location)->first();
                if (!$modelCity) {
                    return redirect()->route('index');
                }
                $location = $modelCity->toArray();
                $modelHotels = Hotel::where('city', $modelCity->name)
                    ->where('country', $modelCity->country)
                    // ->orderBy('number_of_reviews', 'DESC')
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
                    ->simplePaginate(config('content.hotels_pagination_items_per_page'))
                    ->withQueryString();

                $hotels = [];
                foreach ($modelHotels as $modelHotel) {
                    $hotel = $modelHotel->toArray();
                    $hotel['photos'] = json_decode($hotel['photos']);
                    $hotels[] = $hotel;
                }
                $links = $modelHotels->links('components.main.components.simple-pagination')->render();
            }

            if ($type == config('content.location_term_state') || $type == 'state') {
                $modelState = State::with('continent', 'country')->where('slug', $location)->first();
                if (!$modelState) {
                    return redirect()->route('index');
                }
                $location = $modelState->toArray();
                $modelHotels = Hotel::where('state', $modelState->name)
                    ->where('country', $modelState->country)
                    // ->orderBy('number_of_reviews', 'DESC')
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
                    ->simplePaginate(config('content.hotels_pagination_items_per_page'))
                    ->withQueryString();

                $hotels = [];
                foreach ($modelHotels as $modelHotel) {
                    $hotel = $modelHotel->toArray();
                    $hotel['photos'] = json_decode($hotel['photos']);
                    $hotels[] = $hotel;
                }
                $links = $modelHotels->links('components.main.components.simple-pagination')->render();
            }

            if ($request->expectsJson()) {
                $resultsHTML = view('main.contents.json-location', compact('type', 'location', 'hotels', 'links'))->render();
                return [
                    'results' => $resultsHTML,
                    'success' => true,
                ];
            }

            // Generate cache
            if (empty($queryStar) && empty($queryMinPrice) && empty($queryMaxPrice) && $querySortBy == 'popular') {
                $cacheTags = [];
                if ($type == config('content.location_term_city')) {
                    $cacheTags[] = '[city:' . $location['id'] . ']';
                }
                if ($type == config('content.location_term_state')) {
                    $cacheTags[] = '[state:' . $location['id'] . ']';
                }
                $cacheTags[] = '[country:' . $location['country']['id'] . ']';
                $cacheTags[] = '[continent:' . $location['continent']['id'] . ']';
    
                CacheSystemDB::generate($cacheKey, compact('type', 'location', 'hotels', 'links'), [
                    'hotels' => 'hotel',
                ], $cacheTags);
            }
            // [END] Generate cache
        }

        if ($type == 'city' || $type == config('content.location_term_city')) {
            $this->cityTotalViewsHandler($location['id']);
        }

        if ($type == 'state' || $type == config('content.location_term_state')) {
            $this->stateTotalViewsHandler($location['id']);
        }

        return view('main.contents.hotel-location', compact('type', 'location', 'hotels', 'links', 'currentPage'));
    }

    private function cityTotalViewsHandler($cityID)
    {
        $city = City::where('id', $cityID)->first();
        if ($city) {
            $city->increment('total_views');
        }
    }

    private function stateTotalViewsHandler($stateID)
    {
        $state = State::where('id', $stateID)->first();
        if ($state) {
            $state->increment('total_views');
        }
    }
}
