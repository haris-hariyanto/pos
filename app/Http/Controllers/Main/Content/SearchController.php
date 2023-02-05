<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;
use Illuminate\Support\Facades\DB;

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
        $querySortBy = $request->query('sort-by', 'recommended');

        $results = Hotel::where(function ($query) use ($querySearch) {
                $query->where(function ($subQuery) use ($querySearch) {
                    foreach ($querySearch as $querySearchPart) {
                        $subQuery->where('name', 'like', '%' . $querySearchPart . '%');
                    }
                });
            })
            ->when($queryStar, function ($query, $queryStar) {
                $queryStar = explode(',', $queryStar);
                $query->where(function ($subQuery) use ($queryStar) {
                    foreach ($queryStar as $star) {
                        if ($star == 'unrated') {
                            $subQuery->orWhereNull('star_rating');
                        }
                        else {
                            $subQuery->orWhere('star_rating', 'like', $star . '%');
                        }
                    }
                });
            })
            ->when($queryMinPrice, function ($query, $queryMinPrice) {
                if (is_numeric($queryMinPrice)) {
                    $query->where('price', '>=', $queryMinPrice);
                }
            })
            ->when($queryMaxPrice, function ($query, $queryMaxPrice) {
                if (is_numeric($queryMaxPrice)) {
                    $query->where('price', '<=', $queryMaxPrice);
                }
            })
            ->when($querySortBy == 'recommended', function ($query) {
                $query->orderBy('number_of_reviews', 'DESC');
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

        return view('main.contents.search-places', compact('query', 'results'));
    }
}
