<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Hotel\HotelPlace;
use App\Helpers\CacheSystem;

class PlaceController extends Controller
{
    public function index(Request $request, $place)
    {
        $currentPage = $request->query('page', 1);

        $cacheKey = 'place' . $place . 'page' . $currentPage;
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            $modelPlace = Place::with('continent', 'country')->where('slug', $place)->first();
            if (!$modelPlace) {
                return redirect()->route('index');
            }
            $place = $modelPlace->toArray();

            $modelHotels = HotelPlace::with('hotel')
                ->where('place_id', $modelPlace->id)
                ->orderBy('m_distance', 'ASC')
                ->simplePaginate(25);
            $links = $modelHotels->links('components.main.components.simple-pagination')->render();

            $hotelsFound = HotelPlace::where('place_id', $modelPlace->id)
                ->count();

            $hotels = [];
            foreach ($modelHotels as $modelHotel) {
                $hotel = $modelHotel->toArray();
                $hotel['hotel']['photos'] = json_decode($hotel['hotel']['photos']);
                $hotels[] = $hotel;
            }

            // Generate cache
            CacheSystem::generate($cacheKey, compact('place', 'hotels', 'links', 'hotelsFound'));
        }

        return view('main.contents.place', compact('place', 'hotels', 'links', 'hotelsFound'));
    }
}