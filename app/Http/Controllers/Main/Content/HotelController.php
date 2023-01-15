<?php

namespace App\Http\Controllers\Main\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel\Hotel;

class HotelController extends Controller
{
    public function index($hotel)
    {
        $isCachedData = false;
        if ($isCachedData) {
            
        }
        else {
            $modelHotel = Hotel::with('continent', 'country', 'state', 'city')->where('slug', $hotel)->first();
            if (!$modelHotel) {
                return redirect()->route('index');
            }
            $hotel = $modelHotel->toArray();
            $hotel['photos'] = json_decode($hotel['photos']);
        }

        if (config('app.locale') == 'id') {
            $paragraphs = $this->generateArticleID($hotel);
        }
        else {
            $paragraphs = [];
        }

        return view('main.contents.hotel', compact('hotel', 'paragraphs'));
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

        $results[] = $paragraph1;

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

        if (!empty($hotelData['rates_from']) && !empty($hotelData['rates_currency'])) {
            if ($hotelData['rates_currency'] == 'IDR') {
                $paragraph2 .= 'Harga kamar mulai dari ' . number_format($hotelData['rates_from'], 0, ',', '.') . ' ' . $hotelData['rates_currency'] . '.';
            }
            else {
                $paragraph2 .= 'Harga kamar mulai dari ' . number_format($hotelData['rates_from'], 0, '.', ',') . ' ' . $hotelData['rates_currency'] . '.';
            }
        }
        elseif (empty($hotelData['rates_from']) && !empty($hotelData['rates_from_exclusive']) && !empty($hotelData['rates_currency'])) {
            if ($hotelData['rates_currency'] == 'IDR') {
                $paragraph2 .= 'Harga kamar mulai dari ' . number_format($hotelData['rates_from_exclusive'], 0, ',', '.') . ' ' . $hotelData['rates_currency'] . '.';
            }
            else {
                $paragraph2 .= 'Harga kamar mulai dari ' . number_format($hotelData['rates_from_exclusive'], 0, '.', ',') . ' ' . $hotelData['rates_currency'] . '.';
            }
        }

        $results[] = $paragraph2;

        return $results;
    }
}
