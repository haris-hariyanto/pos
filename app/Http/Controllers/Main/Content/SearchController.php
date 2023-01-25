<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->query('q');
        if (!$query) {
            return redirect()->route('index');
        }

        $querySearch = $query;
        $querySearch = preg_replace('/\s+/', ' ', $querySearch);
        $querySearch = explode(' ', $querySearch);

        $results = Place::where('hotels_nearby', '>', 0)
            ->where(function ($query) use ($querySearch) {
                $query->where(function ($subQuery) use ($querySearch) {
                    foreach ($querySearch as $querySearchPart) {
                        $subQuery->where('name', 'like', '%' . $querySearchPart . '%');
                    }
                })->orWhere(function ($subQuery) use ($querySearch) {
                    foreach ($querySearch as $querySearchPart) {
                        $subQuery->where('address', 'like', '%' . $querySearchPart . '%');
                    }
                })->orWhere('country', 'like', '%' . $querySearchPart . '%');
            })
            ->orderBy('hotels_nearby', 'DESC')
            ->simplePaginate(24)
            ->withQueryString();

        return view('main.contents.search', compact('query', 'results'));
    }
}
