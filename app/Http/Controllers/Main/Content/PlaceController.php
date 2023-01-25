<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;
use App\Helpers\CacheSystem;
use App\Helpers\StructuredData;

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

            /*
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
            */

            $latitude = explode('.', $place['latitude']);
            $longitude = explode('.', $place['longitude']);

            $modelHotels = Hotel::where('latitude', 'like', $latitude[0] . '.' . substr($latitude[1], 0, 1) . '%')
                ->where('longitude', 'like', $longitude[0] . '.' . substr($longitude[1], 0, 1) . '%')
                ->where('overview', '<>', '')
                ->where('number_of_reviews', '>', 0)
                ->where(function ($query) {
                    $query->whereNotNull('rates_from')
                        ->orWhereNotNull('rates_from_exclusive');
                })
                ->whereNotNull('star_rating')
                ->simplePaginate(25);
            $links = $modelHotels->links('components.main.components.simple-pagination')->render();
            
            $hotels = [];
            foreach ($modelHotels as $modelHotel) {
                $hotel = [];
                $hotel['hotel'] = $modelHotel->toArray();
                $hotel['hotel']['photos'] = json_decode($hotel['hotel']['photos']);

                $distanceKM = $this->distance($place['latitude'], $place['longitude'], $modelHotel->latitude, $modelHotel->longitude, 'K');
                $hotel['m_distance'] = round($distanceKM * 1000, 0);
                $hotels[] = $hotel;
            }

            $hotels = collect($hotels)->sortBy('m_distance')->toArray();

            // $hotelsFound = $place['hotels_nearby'];

            // Generate cache
            CacheSystem::generate($cacheKey, compact('place', 'hotels', 'links'));
        }

        $structuredData = new StructuredData();
        $structuredData->breadcrumb([
            __('Home') => route('index'),
            $place['name'] => ''
        ]);

        return view('main.contents.place', compact('place', 'hotels', 'links', 'currentPage', 'structuredData'));
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
              return $miles;
            }
    }
}