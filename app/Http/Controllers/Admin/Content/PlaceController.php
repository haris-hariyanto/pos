<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Hotel\HotelPlace;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Places') => '',
        ];

        return view('admin.content.places.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');

        $placesCount = Place::where('type', 'PLACE')
            ->where('hotels_nearby', '>', 0)
            ->count();

        $places = Place::where('type', 'PLACE')
            ->where('hotels_nearby', '>', 0)
            ->when($querySearch, function ($query) use ($querySearch) {
                $query->where('name', 'like', '%' . $querySearch . '%');
            });

        if ($querySearch) {
            $placesCountFiltered = $places->count();
        }
        else {
            $placesCountFiltered = $placesCount;
        }

        $places = $places->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();

        return [
            'total' => $placesCountFiltered,
            'totalNotFiltered' => $placesCount,
            'rows' => $places->map(function ($place) {
                return [
                    'id' => $place->id,
                    'slug' => $place->slug,
                    'name' => $place->name,
                    'country' => $place->country,
                    'continent' => $place->continent,
                    'hotels_nearby' => $place->hotels_nearby,
                    'menu' => view('admin.content.places._menu', ['place' => $place])->render(),
                ];
            }),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Place $place)
    {
        HotelPlace::where('place_id', $place->id)->delete();
        $place->delete();

        return redirect()->back()->with('success', __('Place has been deleted!'));
    }
}
