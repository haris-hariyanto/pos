<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\HotelPlace;
use App\Helpers\Text;
use App\Helpers\Image;
use App\Models\Location\Place;
use App\Helpers\CacheSystem;
use App\Helpers\StructuredData;

class HotelController extends Controller
{
    public function index($hotel)
    {
        $cacheKey = 'hotel' . $hotel;
        $cacheData = CacheSystem::get($cacheKey);

        if ($cacheData) {
            extract($cacheData);
        }
        else {
            $modelHotel = Hotel::with('continent', 'country', 'state', 'city', 'reviews')->where('slug', $hotel)->first();
            if (!$modelHotel) {
                return redirect()->route('index');
            }
            $hotel = $modelHotel->toArray();
            $hotel['photos'] = json_decode($hotel['photos']);

            $latitude = explode('.', $hotel['latitude']);
            $longitude = explode('.', $hotel['longitude']);

            $nearbyPlacesModel = Place::where('latitude', 'like', $latitude[0] . '.' . substr($latitude[1], 0, 1) . '%')
                ->where('longitude', 'like', $longitude[0] . '.' . substr($longitude[1], 0, 1) . '%')
                ->get();
            $nearbyPlaces = [];
            foreach ($nearbyPlacesModel as $nearbyPlaceModel) {
                $nearbyPlacesData = [];
                $nearbyPlacesData['place'] = $nearbyPlaceModel->toArray();

                $distanceKM = $this->distance($nearbyPlaceModel->latitude, $nearbyPlaceModel->longitude, $hotel['latitude'], $hotel['longitude'], 'K');
                $nearbyPlacesData['m_distance'] = round($distanceKM * 1000, 0);

                $nearbyPlaces[] = $nearbyPlacesData;
            }
            // dd($nearbyPlacesModel);

            /*
            $nearbyPlaces = HotelPlace::with('place')
                ->where('hotel_id', $modelHotel->id)
                ->get()
                ->toArray();
            */
            
            // Generate cache
            CacheSystem::generate($cacheKey, compact('hotel', 'nearbyPlaces'));
        }

        if (config('app.locale') == 'id') {
            $paragraphs = $this->generateArticleID($hotel);
        }
        else {
            $paragraphs = $this->generateArticleEN($hotel);
        }

        $structuredData = new StructuredData();
        $structuredData->breadcrumb([
            __('Home') => route('index'),
            $hotel['name'] => ''
        ]);

        $price = null;
        if (!empty($hotel['price']) && !empty($hotel['rates_currency'])) {
            $price = Text::price($hotel['price'], $hotel['rates_currency']);
        }

        $structuredData->hotel([
            'name' => $hotel['name'],
            'description' => $hotel['overview'],
            'photo' => !empty($hotel['photos']) && !empty($hotel['photos'][0]) ? Image::removeQueryParameters($hotel['photos'][0]) : null,
            'price' => $price,
            'star' => $hotel['star_rating'],
            'country' => !empty($hotel['country']) && !empty($hotel['country']['name']) ? $hotel['country']['name'] : null,
            'city' => !empty($hotel['city']) && !empty($hotel['city']['name']) ? $hotel['city']['name'] : null,
            'state' => !empty($hotel['state']) && !empty($hotel['state']['name']) ? $hotel['state']['name'] : null,
            'postalCode' => $hotel['zipcode'],
            'address' => $hotel['address_line_1'],
            'reviews' => $hotel['reviews'],
        ]);

        return view('main.contents.hotel', compact('hotel', 'paragraphs', 'nearbyPlaces', 'structuredData'));
    }

    private function generateArticleEN($hotelData)
    {
        $results = [];

        // Paragraph 1
        $paragraph1 = '';
        if (!empty($hotelData['star_rating']) && !empty($hotelData['city']) && !empty($hotelData['city']['name'])) {
            $paragraph1 .= $hotelData['name'] . ' is a ' . $hotelData['star_rating'] . '-star hotel located in ' . $hotelData['city']['name'] . '.';
        }
        elseif (!empty($hotelData['city']) && !empty($hotelData['city']['name'])) {
            $paragraph1 .= $hotelData['name'] . ' is a hotel located in ' . $hotelData['city']['name'] . '.';
        }

        $paragraph1 .= ' ';

        if (!empty($hotelData['brand']) && !empty($hotelData['chain'])) {
            $paragraph1 .= 'The hotel is part of the renowned ' . $hotelData['brand'] . ' brand, which is owned by the ' . $hotelData['chain'] . ' chain of hotels.';
        }
        elseif (!empty($hotelData['brand'])) {
            $paragraph1 .= 'The hotel is part of the renowned ' . $hotelData['brand'] . ' brand.';
        }
        elseif (!empty($hotelData['chain'])) {
            $paragraph1 .= 'The hotel is part of the ' . $hotelData['chain'] . ' chain of hotels.';
        }

        $paragraph1 .= ' ';

        if (!empty($hotelData['year_opened']) && !empty($hotelData['year_renovated'])) {
            $paragraph1 .= $hotelData['name'] . ' was first opened in ' . $hotelData['year_opened'] . ' and has since undergone a renovation in ' . $hotelData['year_renovated'] . '.';
        }
        elseif (!empty($hotelData['year_opened'])) {
            $paragraph1 .= $hotelData['name'] . ' was first opened in ' . $hotelData['year_opened'] . '.';
        }
        elseif (!empty($hotelData['year_renovated'])) {
            $paragraph1 .= $hotelData['name'] . ' underwent a renovation in ' . $hotelData['year_renovated'] . ', ensuring that all of the rooms and common areas are up-to-date and well-maintained.';
        }

        $paragraph1 .= ' ';

        if (!empty($hotelData['number_of_rooms']) && !empty($hotelData['number_of_floors'])) {
            $paragraph1 .= 'The hotel boasts ' . $hotelData['number_of_floors'] . ' floors and ' . $hotelData['number_of_rooms'] . ' rooms, providing ample space for travelers of all types.';
        }
        elseif (!empty($hotelData['number_of_rooms'])) {
            $paragraph1 .= 'The hotel features ' . $hotelData['number_of_rooms'] . ' spacious and well-appointed rooms.';
        }
        elseif (!empty($hotelData['number_of_floors'])) {
            $paragraph1 .= 'The hotel features ' . $hotelData['number_of_floors'] . ' floors, with a variety of rooms options to choose from.';
        }

        if (!empty(trim($paragraph1))) {
            $results[] = $paragraph1;
        }

        // Paragraph 2
        $paragraph2 = '';
        if (!empty($hotelData['check_in']) && !empty($hotelData['check_out'])) {
            $paragraph2 .= 'Check-in begins at ' . $hotelData['check_in'] . ' and check-out is at ' . $hotelData['check_out'] . ', providing guests with a comfortable stay.';
        }
        elseif (!empty($hotelData['check_in'])) {
            $paragraph2 .= 'Check-in at the ' . $hotelData['name'] . ' begins at ' . $hotelData['check_in'] . ', giving guests plenty of time to explore the city before settling into their room.';
        }
        elseif (!empty($hotelData['check_out'])) {
            $paragraph2 .= 'Check-out time at the ' . $hotelData['name'] . ' is at ' . $hotelData['check_out'] . ', giving guests plenty of time to enjoy their last day before departing.';
        }

        $paragraph2 .= ' ';

        if (!empty($hotelData['price']) && !empty($hotelData['rates_currency'])) {
            $paragraph2 .= 'The rates at the ' . $hotelData['name'] . ' start form ' . Text::price($hotelData['price'], $hotelData['rates_currency']) . ', making it an affordable option.';
        }

        if (!empty(trim($paragraph2))) {
            $results[] = $paragraph2;
        }

        return $results;
    }

    private function generateArticleID($hotelData)
    {
        $results = [];

        // Paragraph 1
        $paragraph1 = '';
        if (!empty($hotelData['star_rating']) && !empty($hotelData['brand']) && !empty($hotelData['chain'])) {
            $paragraph1 .= $hotelData['name'] . ' adalah sebuah hotel bintang ' . $hotelData['star_rating'] . ' yang berada di bawah brand ' . $hotelData['brand'] . ' dan jaringan hotel ' . $hotelData['chain'] . '.';
        }
        elseif (!empty($hotelData['star_rating']) && !empty($hotelData['brand'])) {
            $paragraph1 .= $hotelData['name'] . ' adalah sebuah hotel bintang ' . $hotelData['star_rating'] . ' yang berada di bawah brand ' . $hotelData['brand'] . '.';
        }
        elseif (!empty($hotelData['star_rating']) && !empty($hotelData['chain'])) {
            $paragraph1 .= $hotelData['name'] . ' adalah sebuah hotel bintang ' . $hotelData['star_rating'] . ' yang berada di bawah jaringan hotel ' . $hotelData['chain'] . '.';
        }
        elseif (!empty($hotelData['star_rating']) && !empty($hotelData['city']) && !empty($hotelData['city']['name'])) {
            $paragraph1 .= $hotelData['name'] . ' adalah sebuah hotel bintang ' . $hotelData['star_rating'] . ' yang berada di ' . $hotelData['city']['name'] . '.';
        }

        $paragraph1 .= ' ';

        if (!empty($hotelData['year_opened']) && !empty($hotelData['year_renovated'])) {
            $paragraph1 .= 'Hotel ini didirikan pada tahun ' . $hotelData['year_opened'] . ' dan telah mengalami renovasi pada tahun ' . $hotelData['year_renovated'] . '.';
        }
        elseif (!empty($hotelData['year_opened'])) {
            $paragraph1 .= 'Hotel ini didirikan pada tahun ' . $hotelData['year_opened'] . '.';
        }
        elseif (!empty($hotelData['year_renovated'])) {
            $paragraph1 .= 'Pada tahun ' . $hotelData['year_renovated'] . ', hotel ini melakukan renovasi untuk memberikan kenyamanan dan fasilitas yang lebih baik bagi para tamu.';
        }

        $paragraph1 .= ' ';

        if (!empty($hotelData['number_of_rooms']) && !empty($hotelData['number_of_floors'])) {
            $paragraph1 .= $hotelData['name'] . ' memiliki ' . number_format($hotelData['number_of_rooms'], 0, ',', '.') . ' kamar yang tersebar di ' . $hotelData['number_of_floors'] . ' lantai.';
        }
        elseif (!empty($hotelData['number_of_rooms'])) {
            $paragraph1 .= $hotelData['name'] . ' memiliki ' . number_format($hotelData['number_of_rooms'], 0, ',', '.') . ' kamar yang tersedia untuk tamu.';
        }
        elseif (!empty($hotelData['number_of_floors'])) {
            $paragraph1 .= $hotelData['name'] . ' memiliki ' . $hotelData['number_of_floors'] . ' lantai.';
        }

        if (!empty(trim($paragraph1))) {
            $results[] = $paragraph1;
        }

        // Paragraph 2
        $paragraph2 = '';
        if (!empty($hotelData['check_in']) && !empty($hotelData['check_out'])) {
            $paragraph2 .= 'Check-in di ' . $hotelData['name'] . ' dimulai pada pukul ' . $hotelData['check_in'] . ' dan checkout pada pukul ' . $hotelData['check_out'] . '.';
        }
        elseif (!empty($hotelData['check_in'])) {
            $paragraph2 .= 'Check-in di ' . $hotelData['name'] . ' dimulai pada pukul ' . $hotelData['check_in'] . '.';
        }
        elseif (!empty($hotelData['check_out'])) {
            $paragraph2 .= 'Checkout di ' . $hotelData['name'] . ' maksimal dilakukan pada pukul ' . $hotelData['check_out'] . '.';
        }

        $paragraph2 .= ' ';

        if (!empty($hotelData['price']) && !empty($hotelData['rates_currency'])) {
            $paragraph2 .= 'Harga kamar mulai dari ' . Text::price($hotelData['price'], $hotelData['rates_currency']) . '.';
        }

        if (!empty(trim($paragraph2))) {
            $results[] = $paragraph2;
        }

        return $results;
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
