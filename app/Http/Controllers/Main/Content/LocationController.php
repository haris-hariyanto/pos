<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Hotel\Hotel;
use App\Helpers\CacheSystem;

class LocationController extends Controller
{
    public function index(Request $request, $type, $location)
    {
        $currentPage = $request->query('page', 1);

        $cacheKey = 'location' . $type . $location . 'page' . $currentPage;
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            if (!in_array($type, ['city', 'state', config('content.location_term_city'), config('content.location_term_state')])) {
                return redirect()->route('index');
            }
            
            if ($type == config('content.location_term_city')) {
                $modelCity = City::with('continent', 'country')->where('slug', $location)->first();
                if (!$modelCity) {
                    return redirect()->route('index');
                }
                $location = $modelCity->toArray();
                $modelHotels = Hotel::where('city', $modelCity->name)
                    ->where('country', $modelCity->country)
                    ->orderBy('number_of_reviews', 'DESC')
                    ->simplePaginate(25);
                $hotels = [];
                foreach ($modelHotels as $modelHotel) {
                    $hotel = $modelHotel->toArray();
                    $hotel['photos'] = json_decode($hotel['photos']);
                    $hotels[] = $hotel;
                }
                $links = $modelHotels->links('components.main.components.simple-pagination')->render();
            }

            if ($type == config('content.location_term_state')) {
                $modelState = State::with('continent', 'country')->where('slug', $location)->first();
                if (!$modelState) {
                    return redirect()->route('index');
                }
                $location = $modelState->toArray();
                $modelHotels = Hotel::where('state', $modelState->name)
                    ->where('country', $modelState->country)
                    ->orderBy('number_of_reviews', 'DESC')
                    ->simplePaginate(25);
                $hotels = [];
                foreach ($modelHotels as $modelHotel) {
                    $hotel = $modelHotel->toArray();
                    $hotel['photos'] = json_decode($hotel['photos']);
                    $hotels[] = $hotel;
                }
                $links = $modelHotels->links('components.main.components.simple-pagination')->render();
            }

            // Generate cache
            CacheSystem::generate($cacheKey, compact('type', 'location', 'hotels', 'links'));
        }

        return view('main.contents.hotel-location', compact('type', 'location', 'hotels', 'links', 'currentPage'));
    }
}
