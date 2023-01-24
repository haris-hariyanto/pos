<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MetaData;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MetaData::truncate();

        if (config('app.locale') == 'id') {
            $pagesettings_home_page_title = 'Temukan Hotel dengan Harga Terbaik di Tempat Populer';
            $pagesettings_home_meta_data = view('stubs.metadata', [
                'description' => '[appname] adalah website untuk mencari hotel di sekitar lokasi-lokasi penting di Indonesia dan di seluruh dunia. Temukan hotel dengan harga terbaik.',
                'title' => $pagesettings_home_page_title,
            ]);
            $pagesettings_home_brief_paragraph = 'Cari hotel di sekitar lokasi-lokasi populer di seluruh dunia';

            $pagesettings_hotel_page_title = '[hotel_name]';
            $pagesettings_hotel_meta_data = view('stubs.metadata', [
                'description' => '[hotel_name], [hotel_address]',
                'title' => $pagesettings_hotel_page_title,
                'image' => '[hotel_image]',
                'twitter_card' => 'summary_large_image',
                'image_alt' => '[hotel_name]',
            ]);
            $pagesettings_hotel_heading = '[hotel_name]';

            $pagesettings_place_page_title = '[total_hotels]+ Hotel Dekat [place_name]';
            $pagesettings_place_meta_data = view('stubs.metadata', [
                'description' => 'Daftar rekomendasi hotel terbaik dekat [place_name]. Ditemukan [total_hotels] hotel, dari penginapan mewah hingga opsi yang lebih terjangkau.',
                'title' => $pagesettings_place_page_title,
            ]);
            $pagesettings_place_heading = $pagesettings_place_page_title;

            $pagesettings_continent_page_title = 'Daftar Negara di [continent_name]';
            $pagesettings_continent_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_continent_page_title,
                'title' => $pagesettings_continent_page_title,
            ]);
            $pagesettings_continent_heading = $pagesettings_continent_page_title;

            $pagesettings_country_page_title = 'Daftar Kota, Daerah, dan Tempat-Tempat Populer di [country_name]';
            $pagesettings_country_meta_data = view('stubs.metadata', [
                'description' => 'Daftar kota, daerah, tempat-tempat populer, dan hotel-hotel populer di [country_name]',
                'title' => $pagesettings_country_page_title,
            ]);
            $pagesettings_country_heading = $pagesettings_country_page_title;

            $pagesettings_country_states_page_title = 'Daftar Provinsi / Negara Bagian di [country_name]';
            $pagesettings_country_states_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_country_states_page_title,
                'title' => $pagesettings_country_states_page_title,
            ]);
            $pagesettings_country_states_heading = $pagesettings_country_states_page_title;

            $pagesettings_country_cities_page_title = 'Daftar Kota di [country_name]';
            $pagesettings_country_cities_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_country_cities_page_title,
                'title' => $pagesettings_country_cities_page_title,
            ]);
            $pagesettings_country_cities_heading = $pagesettings_country_cities_page_title;

            $pagesettings_country_places_page_title = 'Daftar [place_category] di [country_name]';
            $pagesettings_country_places_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_country_places_page_title,
                'title' => $pagesettings_country_places_page_title,
            ]);
            $pagesettings_country_places_heading = $pagesettings_country_places_page_title;

            $pagesettings_city_page_title = 'Daftar Hotel Terbaik di [city_name], [country_name]';
            $pagesettings_city_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_city_page_title,
                'title' => $pagesettings_city_page_title,
            ]);
            $pagesettings_city_heading = $pagesettings_city_page_title;

            $pagesettings_state_page_title = 'Daftar Hotel Terbaik di [state_name], [country_name]';
            $pagesettings_state_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_state_page_title,
                'title' => $pagesettings_state_page_title,
            ]);
            $pagesettings_state_heading = $pagesettings_state_page_title;
        }
        else {
            $pagesettings_home_page_title = 'Find Your Perfect Hotel';
            $pagesettings_home_meta_data = view('stubs.metadata', [
                'description' => 'Search and compare hotels from top providers to find the best deals and amenities for your next trip. Book your stay with confidence on our easy-to-use hotel finder website.',
                'title' => $pagesettings_home_page_title,
            ]);
            $pagesettings_home_brief_paragraph = 'Find hotels around popular locations around the world';

            $pagesettings_hotel_page_title = '[hotel_name]';
            $pagesettings_hotel_meta_data = view('stubs.metadata', [
                'description' => '[hotel_name], [hotel_address]',
                'title' => $pagesettings_hotel_page_title,
                'image' => '[hotel_image]',
                'twitter_card' => 'summary_large_image',
                'image_alt' => '[hotel_name]',
            ]);
            $pagesettings_hotel_heading = '[hotel_name]';

            $pagesettings_place_page_title = '[total_hotels]+ Hotels Near [place_name]';
            $pagesettings_place_meta_data = view('stubs.metadata', [
                'description' => 'Discover the top hotels near the [place_name]. Over [total_hotels] hotels, from luxury accommodations to budget-friendly options.',
                'title' => $pagesettings_place_page_title,
            ]);
            $pagesettings_place_heading = $pagesettings_place_page_title;

            $pagesettings_continent_page_title = 'List of Countries in [continent_name]';
            $pagesettings_continent_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_continent_page_title,
                'title' => $pagesettings_continent_page_title,
            ]);
            $pagesettings_continent_heading = $pagesettings_continent_page_title;

            $pagesettings_country_page_title = 'List of Cities, States, and Popular Places in [country_name]';
            $pagesettings_country_meta_data = view('stubs.metadata', [
                'description' => 'List of Cities, States, Popular Places, and Popular Hotels in [country_name]',
                'title' => $pagesettings_country_page_title,
            ]);
            $pagesettings_country_heading = $pagesettings_country_page_title;

            $pagesettings_country_states_page_title = 'List of States in [country_name]';
            $pagesettings_country_states_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_country_states_page_title,
                'title' => $pagesettings_country_states_page_title,
            ]);
            $pagesettings_country_states_heading = $pagesettings_country_states_page_title;

            $pagesettings_country_cities_page_title = 'List of Cities in [country_name]';
            $pagesettings_country_cities_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_country_cities_page_title,
                'title' => $pagesettings_country_cities_page_title,
            ]);
            $pagesettings_country_cities_heading = $pagesettings_country_cities_page_title;

            $pagesettings_country_places_page_title = 'List of [place_category] in [country_name]';
            $pagesettings_country_places_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_country_places_page_title,
                'title' => $pagesettings_country_places_page_title,
            ]);
            $pagesettings_country_places_heading = $pagesettings_country_places_page_title;

            $pagesettings_city_page_title = 'Best Hotels in [city_name], [country_name]';
            $pagesettings_city_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_city_page_title,
                'title' => $pagesettings_city_page_title,
            ]);
            $pagesettings_city_heading = $pagesettings_city_page_title;

            $pagesettings_state_page_title = 'Best Hotels in [state_name], [country_name]';
            $pagesettings_state_meta_data = view('stubs.metadata', [
                'description' => $pagesettings_state_page_title,
                'title' => $pagesettings_state_page_title,
            ]);
            $pagesettings_state_heading = $pagesettings_state_page_title;
        }

        MetaData::insert([
            ['key' => 'settings__website_name', 'value' => config('app.name')],
            ['key' => 'settings__header_script', 'value' => ''],
            ['key' => 'settings__footer_script', 'value' => ''],
            ['key' => 'settings__agoda_suffix', 'value' => ''],
            
            ['key' => 'pagesettings_home_page_title', 'value' => $pagesettings_home_page_title],
            ['key' => 'pagesettings_home_meta_data', 'value' => $pagesettings_home_meta_data],
            ['key' => 'pagesettings_home_brief_paragraph', 'value' => $pagesettings_home_brief_paragraph],

            ['key' => 'pagesettings_hotel_page_title', 'value' => $pagesettings_hotel_page_title],
            ['key' => 'pagesettings_hotel_meta_data', 'value' => $pagesettings_hotel_meta_data],
            ['key' => 'pagesettings_hotel_heading', 'value' => $pagesettings_hotel_heading],

            ['key' => 'pagesettings_place_page_title', 'value' => $pagesettings_place_page_title],
            ['key' => 'pagesettings_place_meta_data', 'value' => $pagesettings_place_meta_data],
            ['key' => 'pagesettings_place_heading', 'value' => $pagesettings_place_heading],

            ['key' => 'pagesettings_continent_page_title', 'value' => $pagesettings_continent_page_title],
            ['key' => 'pagesettings_continent_meta_data', 'value' => $pagesettings_continent_meta_data],
            ['key' => 'pagesettings_continent_heading', 'value' => $pagesettings_continent_heading],

            ['key' => 'pagesettings_country_page_title', 'value' => $pagesettings_country_page_title],
            ['key' => 'pagesettings_country_meta_data', 'value' => $pagesettings_country_meta_data],
            ['key' => 'pagesettings_country_heading', 'value' => $pagesettings_country_heading],

            ['key' => 'pagesettings_country_states_page_title', 'value' => $pagesettings_country_states_page_title],
            ['key' => 'pagesettings_country_states_meta_data', 'value' => $pagesettings_country_states_meta_data],
            ['key' => 'pagesettings_country_states_heading', 'value' => $pagesettings_country_states_heading],

            ['key' => 'pagesettings_country_cities_page_title', 'value' => $pagesettings_country_cities_page_title],
            ['key' => 'pagesettings_country_cities_meta_data', 'value' => $pagesettings_country_cities_meta_data],
            ['key' => 'pagesettings_country_cities_heading', 'value' => $pagesettings_country_cities_heading],

            ['key' => 'pagesettings_country_places_page_title', 'value' => $pagesettings_country_places_page_title],
            ['key' => 'pagesettings_country_places_meta_data', 'value' => $pagesettings_country_places_meta_data],
            ['key' => 'pagesettings_country_places_heading', 'value' => $pagesettings_country_places_heading],

            ['key' => 'pagesettings_city_page_title', 'value' => $pagesettings_city_page_title],
            ['key' => 'pagesettings_city_meta_data', 'value' => $pagesettings_city_meta_data],
            ['key' => 'pagesettings_city_heading', 'value' => $pagesettings_city_heading],

            ['key' => 'pagesettings_state_page_title', 'value' => $pagesettings_state_page_title],
            ['key' => 'pagesettings_state_meta_data', 'value' => $pagesettings_state_meta_data],
            ['key' => 'pagesettings_state_heading', 'value' => $pagesettings_state_heading],
        ]);
    }
}
