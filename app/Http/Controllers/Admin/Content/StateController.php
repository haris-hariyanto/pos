<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\State;

class StateController extends Controller
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
            __('States') => '',
        ];

        return view('admin.content.states.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'asc');
        $querySearch = $request->query('search');

        $statesCount = State::count();

        $states = State::when($querySearch, function ($query) use ($querySearch) {
            $query->where('name', 'like', '%' . $querySearch . '%');
        });
        if ($querySearch) {
            $statesCountFiltered = $states->count();
        }
        else {
            $statesCountFiltered = $statesCount;
        }

        $states = $states->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $statesCountFiltered,
            'totalNotFiltered' => $statesCount,
            'rows' => $states->map(function ($state) {
                return [
                    'id' => $state->id,
                    'slug' => $state->slug,
                    'name' => $state->name,
                    'country' => $state->country,
                    'continent' => $state->continent,
                    'total_views' => $state->total_views,
                    'menu' => view('admin.content.states._menu', ['state' => $state])->render(),
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
    public function edit(State $state)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('States') => route('admin.states.index'),
            __('Edit State') => '',
        ];

        return view('admin.content.states.edit', compact('state', 'breadcrumb'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        $validationRules = [
            'name' => ['required']
        ];

        $validated = $request->validate($validationRules);

        \App\Models\Location\Place::where('country', $state->country)->where('state', $state->name)->update(['state' => $validated['name']]);
        \App\Models\Location\City::where('country', $state->country)->where('state', $state->name)->update(['state' => $validated['name']]);
        \App\Models\Hotel\Hotel::where('country', $state->country)->where('state', $state->name)->update(['state' => $validated['name']]);
        $state->update($validated);

        return redirect()->back()->with('success', __('State has been updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
        $state->cities()->delete();
        $state->hotels()->delete();
        $state->delete();

        return redirect()->back()->with('success', __('State has been deleted!'));
    }
}
