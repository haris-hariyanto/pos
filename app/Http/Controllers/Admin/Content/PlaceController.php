<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Hotel\HotelPlace;
use App\Models\Location\Country;
use App\Models\Location\Category;
use App\Models\Location\CategoryPlace;
use Illuminate\Support\Str;

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
            ->count();

        $places = Place::where('type', 'PLACE')
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
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Places') => route('admin.places.index'),
            __('Add Place') => '',
        ];

        $countries = Country::get();
        $countries = $countries->mapWithKeys(function ($item, $key) {
            return [$item['name'] => $item['name']];
        });

        $categories = collect(config('scraper.place_types_to_fetch'));
        $categories = $categories->mapWithKeys(function ($item, $key) {
                return [$item => __(ucwords(str_replace('_', ' ', $item)))];
            })
            ->prepend(__('Category'), '')
            ->toArray();

        return view('admin.content.places.create', compact('breadcrumb', 'countries', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:512'],
            'longitude' => ['required', 'regex:/^\-?[0-9]{1,}\.[0-9]{1,}$/'],
            'latitude' => ['required', 'regex:/^\-?[0-9]{1,}\.[0-9]{1,}$/'],
            'country' => ['required', 'exists:countries,name'],
            'category' => ['nullable', 'exists:categories,name'],
        ]);

        $baseSlug = Str::slug($validated['name']);
        $finalSlug = $baseSlug;
        $counter = 1;
        while (true) {
            $slugCount = Place::where('slug', $finalSlug)->count();
            if ($slugCount == 0) {
                break;
            }
            $finalSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $country = Country::where('name', $validated['country'])->first();
        $validated['country'] = $country->name;
        $validated['continent'] = $country->continent;

        $place = Place::create([
            'slug' => $finalSlug,
            'name' => $validated['name'],
            'type' => 'PLACE',
            'address' => $validated['address'],
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude'],
            'country' => $validated['country'],
            'continent' => $validated['continent'],
            'gmaps_id' => null,
        ]);

        $category = Category::where('name', $validated['category'])->first();
        if ($category) {
            CategoryPlace::create([
                'category_id' => $category->id,
                'place_id' => $place->id,
                'country' => $validated['country'],
                'continent' => $validated['continent'],
            ]);
        }

        return redirect()->route('admin.places.index')->with('success', __('Place has been added!'));
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
    public function edit(Place $place)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Place') => route('admin.places.index'),
            __('Edit Place') => '',
        ];

        $countries = Country::get();
        $countries = $countries->mapWithKeys(function ($item, $key) {
            return [$item['name'] => $item['name']];
        });

        $categories = collect(config('scraper.place_types_to_fetch'));
        $categories = $categories->mapWithKeys(function ($item, $key) {
                return [$item => __(ucwords(str_replace('_', ' ', $item)))];
            })
            ->prepend(__('Category'), '')
            ->toArray();
        
        $currentCategory = CategoryPlace::where('place_id', $place->id)->first();
        if ($currentCategory) {
            $currentCategory = $currentCategory->category->name;
        }
        else {
            $currentCategory = '';
        }

        return view('admin.content.places.edit', compact('place', 'breadcrumb', 'countries', 'categories', 'currentCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Place $place)
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:512'],
            'longitude' => ['required', 'regex:/^\-?[0-9]{1,}\.[0-9]{1,}$/'],
            'latitude' => ['required', 'regex:/^\-?[0-9]{1,}\.[0-9]{1,}$/'],
            'country' => ['required', 'exists:countries,name'],
            'category' => ['nullable', 'exists:categories,name'],
        ];

        $validated = $request->validate($validationRules);

        $country = Country::where('name', $validated['country'])->first();
        $validated['country'] = $country->name;
        $validated['continent'] = $country->continent;

        $place->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'longitude' => $validated['longitude'],
            'latitude' => $validated['latitude'],
            'country' => $validated['country'],
            'continent' => $validated['continent'],
        ]);

        CategoryPlace::where('place_id', $place->id)->delete();

        if (!empty($validated['category'])) {
            $category = Category::where('name', $validated['category'])->first();
            if ($category) {
                CategoryPlace::create([
                    'category_id' => $category->id,
                    'place_id' => $place->id,
                    'country' => $validated['country'],
                    'continent' => $validated['continent'],
                ]);
            }
        }

        return redirect()->back()->with('success', __('Place has been updated!'));
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
