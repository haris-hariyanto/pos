<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\HotelPlace;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\State;
use Illuminate\Support\Str;
use App\Helpers\CacheSystem;

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

        $hotelsCount = Cache::rememberForever('hotelscount', function () {
            return Hotel::count();
        });

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
    public function edit(Hotel $hotel)
    {
        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Hotels') => route('admin.hotels.index'),
            __('Edit Hotel') => '',
        ];

        $hotel->photos = json_decode($hotel->photos, true);

        $countries = Country::get();
        $countries = $countries->mapWithKeys(function ($country) {
            return [$country->name => $country->name];
        })->toArray();

        return view('admin.content.hotels.edit', compact('hotel', 'breadcrumb', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'formerly_name' => ['nullable', 'max:255'],
            'translated_name' => ['nullable', 'max:255'],
            'star_rating' => ['nullable', 'in:1,1.5,2,2.5,3,3.5,4,4.5,5,unrated'],
            'url' => ['required', 'url'],
            'price' => ['nullable', 'numeric'],
            'rates_currency' => ['required_with:price'],
            'overview' => ['nullable', 'max:2048'],
            'brand' => ['nullable', 'max:255'],
            'chain' => ['nullable', 'max:255'],
            'address_line_1' => ['nullable', 'max:1024'],
            'address_line_2' => ['nullable', 'max:1024'],
            'zipcode' => ['nullable', 'max:32'],
            'check_in' => ['nullable', 'max:16'],
            'check_out' => ['nullable', 'max:16'],
            'number_of_rooms' => ['nullable', 'numeric', 'min:1'],
            'number_of_floors' => ['nullable', 'numeric', 'min:1'],
            'year_opened' => ['nullable', 'numeric'],
            'year_renovated' => ['nullable', 'numeric'],
            'accommodation_type' => ['nullable', 'max:255'],
            'country' => ['required', 'exists:countries,name'],
            'city' => ['required_without:state'],
            'state' => ['required_without:city'],
            'longitude' => ['required'],
            'latitude' => ['required'],
        ]);

        $country = Country::where('name', $validated['country'])->first();
        if ($country) {
            $validated['continent'] = $country->continent;
            $validated['country'] = $country->name;
            $validated['country_iso_code'] = $country->iso_code;

            if (!empty($validated['city'])) {
                // Tambahkan city ke database jika belum ada
                $city = City::where('name', $validated['city'])
                    ->where('country', $validated['country'])
                    ->first();
                if (!$city) {
                    $lastCityID = City::select('id')->orderBy('id', 'DESC')->first();
                    if ($lastCityID) {
                        $lastCityID = $lastCityID->id;
                        $lastCityID++;
                    }
                    else {
                        $lastCityID = 1;
                    }

                    City::create([
                        'slug' => $lastCityID . '-' . Str::slug($validated['city']),
                        'name' => $validated['city'],
                        'state' => !empty($validated['state']) ? $validated['state'] : null,
                        'country' => $validated['country'],
                        'continent' => $validated['continent'],
                    ]);
                }
            } // [END] if

            if (!empty($validated['state'])) {
                // Tambahkan state ke database jika belum ada
                $state = State::where('name', $validated['state'])
                    ->where('country', $validated['country'])
                    ->first();
                if (!$state) {
                    $lastStateID = State::select('id')->orderBy('id', 'DESC')->first();
                    if ($lastStateID) {
                        $lastStateID = $lastStateID->id;
                        $lastStateID++;
                    }
                    else {
                        $lastStateID = 1;
                    }

                    State::create([
                        'slug' => $lastStateID . '-' . Str::slug($validated['state']),
                        'name' => $validated['state'],
                        'country' => $validated['country'],
                        'continent' => $validated['continent'],
                    ]);
                }
            } // [END] if
        }

        if ($validated['star_rating'] == 'unrated') {
            $validated['star_rating'] = null;
        }

        $hotel->update($validated);

        $this->deleteCache($hotel);

        return redirect()->back()->with('success', __('Hotel has been updated!'));
    }

    public function updatePhotos(Request $request, Hotel $hotel) {
        $photosType = $request->photosType;
        
        if ($photosType == 'hotlink') {

            $validated = $request->validate([
                'photos_hotlinks.*' => ['nullable', 'url'],
            ]);
            
            $photos = [];
            foreach ($validated['photos_hotlinks'] as $photo) {
                if (!empty($photo)) {
                    $photos[] = $photo;   
                }
                else {
                    $photos[] = '';
                }
            }

            $photos = json_encode($photos);

            $hotel->update(['photos' => $photos]);

            $this->deleteCache($hotel);

            return redirect()->back()->with('success', __('Hotel has been updated!'));
        }
        elseif ($photosType == 'upload') {

            $validated = $request->validate([
                'photos_uploads.*' => ['nullable', 'file', 'image'],
            ]);

            $photos = [];
            $hotelPhotos = json_decode($hotel->photos, true);
            for ($i = 0; $i < 5; $i++) {
                if (!empty($request->photos_uploads[$i])) {
                    $path = $request->photos_uploads[$i]->store('images', 'public');
                    $fullURL = asset('storage/' . $path);
                    $photos[] = $fullURL;
                }
                else {
                    $photos[] = $hotelPhotos[$i];
                }
            }

            $photos = json_encode($photos);

            $hotel->update(['photos' => $photos]);

            $this->deleteCache($hotel);

            return redirect()->back()->with('success', __('Hotel has been updated!'));

        }
        else {
            return redirect()->route('admin.hotels.edit', [$hotel['slug']]);
        }
    }

    private function deleteCache(Hotel $hotel)
    {
        // Hotel page
        $key = 'hotel' . $hotel->slug;
        CacheSystem::forget($key);

        // Country page
        $country = Country::where('name', $hotel->country)
            ->where('continent', $hotel->continent)
            ->first();
        if ($country) {
            $key = 'country' . $country->slug;
            CacheSystem::forget($key);
        }
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

        Cache::forget('hotelscount');

        return redirect()->back()->with('success', __('Hotel has been deleted!'));
    }
}
