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
        MetaData::insert([
            ['key' => 'settings__website_name', 'value' => config('app.name')],
            ['key' => 'settings__header_script', 'value' => ''],
            ['key' => 'settings__footer_script', 'value' => ''],
            
            ['key' => 'pagesettings_home_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_home_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_hotel_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_hotel_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_place_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_place_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_continent_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_continent_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_country_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_country_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_country_states_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_country_states_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_country_cities_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_country_cities_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_country_places_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_country_places_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_city_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_city_meta_data', 'value' => '<meta name="x" content="x">'],

            ['key' => 'pagesettings_state_page_title', 'value' => 'Home Page Title [appname]'],
            ['key' => 'pagesettings_state_meta_data', 'value' => '<meta name="x" content="x">'],
        ]);
    }
}
