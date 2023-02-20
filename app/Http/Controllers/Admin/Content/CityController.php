<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\City;

class CityController extends Controller
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
            __('Cities') => '',
        ];

        return view('admin.content.cities.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'asc');
        $querySearch = $request->query('search');

        $citiesCount = City::count();

        $cities = City::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%');
        });
        if ($querySearch) {
            $citiesCountFiltered = $cities->count();
        }
        else {
            $citiesCountFiltered = $citiesCount;
        }

        $cities = $cities->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $citiesCountFiltered,
            'totalNotFiltered' => $citiesCount,
            'rows' => $cities->map(function ($city) {
                return [
                    'id' => $city->id,
                    'slug' => $city->slug,
                    'name' => $city->name,
                    'state' => $city->state,
                    'country' => $city->country,
                    'continent' => $city->continent,
                    'total_views' => $city->total_views,
                    'menu' => view('admin.content.cities._menu', ['city' => $city])->render(),
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
    public function destroy(City $city)
    {
        $city->hotels()->delete();
        $city->delete();

        return redirect()->back()->with('success', __('City has been deleted!'));
    }
}
