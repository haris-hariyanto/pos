<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\HotelPlace;

class HotelController extends Controller
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
            __('Hotels') => '',
        ];

        return view('admin.content.hotels.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'asc');
        $querySearch = $request->query('search');

        $hotelsCount = Hotel::count();

        $hotels = Hotel::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%');
        });
        if ($querySearch) {
            $hotelsCountFiltered = $hotels->count();
        }
        else {
            $hotelsCountFiltered = $hotelsCount;
        }

        $hotels = $hotels->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();

        return [
            'total' => $hotelsCountFiltered,
            'totalNotFiltered' => $hotelsCount,
            'rows' => $hotels->map(function ($hotel) {
                return [
                    'id' => $hotel->id,
                    'slug' => $hotel->slug,
                    'name' => $hotel->name,
                    'chain' => $hotel->chain,
                    'brand' => $hotel->brand,
                    'city' => $hotel->city,
                    'state' => $hotel->state,
                    'country' => $hotel->country,
                    'continent' => $hotel->continent,
                    'menu' => view('admin.content.hotels._menu', ['hotel' => $hotel])->render(),
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
    public function destroy(Hotel $hotel)
    {
        HotelPlace::where('hotel_id', $hotel->id)->delete();
        $hotel->delete();

        return redirect()->back()->with('success', __('Hotel has been deleted!'));
    }
}
