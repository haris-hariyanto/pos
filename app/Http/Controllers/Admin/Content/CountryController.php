<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Country;
use App\Helpers\CacheSystemDB;

class CountryController extends Controller
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
            __('Countries') => '',
        ];

        return view('admin.content.countries.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'asc');
        $querySearch = $request->query('search');

        $countriesCount = Country::count();

        $countries = Country::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%');
        });
        if ($querySearch) {
            $countriesCountFiltered = $countries->count();
        }
        else {
            $countriesCountFiltered = $countriesCount;
        }

        $countries = $countries->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $countriesCountFiltered,
            'totalNotFiltered' => $countriesCount,
            'rows' => $countries->map(function ($country) {
                return [
                    'id' => $country->id,
                    'slug' => $country->slug,
                    'name' => $country->name,
                    'continent' => $country->continent,
                    'menu' => view('admin.content.countries._menu', ['country' => $country])->render(),
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
    public function edit(Country $country)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Countries') => route('admin.countries.index'),
            __('Edit Country') => '',
        ];

        return view('admin.content.countries.edit', compact('country', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        $validationRules = [
            'name' => ['required']
        ];

        $validated = $request->validate($validationRules);

        \App\Models\Location\Place::where('country', $country->name)->where('continent', $country->continent)->update(['country' => $validated['name']]);
        \App\Models\Location\City::where('country', $country->name)->where('continent', $country->continent)->update(['country' => $validated['name']]);
        \App\Models\Location\State::where('country', $country->name)->where('continent', $country->continent)->update(['country' => $validated['name']]);
        \App\Models\Hotel\Hotel::where('country', $country->name)->where('continent', $country->continent)->update(['country' => $validated['name']]);
        \App\Models\Location\CategoryPlace::where('country', $country->name)->where('continent', $country->continent)->update(['country' => $validated['name']]);
        $country->update($validated);

        CacheSystemDB::forgetWithTags($country->id, 'country');

        return redirect()->back()->with('success', __('Country has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Country $country)
    {
        CacheSystemDB::forgetWithTags($country->id, 'country');
        
        $country->states()->delete();
        $country->cities()->delete();
        $country->places()->delete();
        $country->hotels()->delete();
        $country->delete();

        return redirect()->back()->with('success', __('Country has been deleted!'));
    }
}
