<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\Category;
use App\Models\Location\Place;
use App\Helpers\CacheSystemDB;

class DeleteCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $choices = [
            'Semua halaman',
            'Halaman Awal',
            'Benua',
            'Negara',
            'Daftar Kota',
            'Daftar Provinsi / State',
            'Daftar Tempat',
        ];

        $cacheToDelete = $this->choice('Pilih cache untuk dihapus', $choices, 0);

        if ($cacheToDelete == $choices[0] || $cacheToDelete == 'Halaman Awal') {
            $this->line('[ * ] Menghapus cache halaman awal');
            CacheSystemDB::forget('homepage');
        }

        if ($cacheToDelete == $choices[0] || $cacheToDelete == 'Benua') {
            $this->line('[ * ] Menghapus cache halaman benua');

            $continents = Continent::get();
            foreach ($continents as $continent) {
                $this->line('[ * ] Menghapus cache halaman benua : ' . $continent->name);
                CacheSystemDB::forget('continent' . $continent->slug);
            }
        }

        if ($cacheToDelete == $choices[0] || $cacheToDelete == 'Negara') {
            $this->line('[ * ] Menghapus cache halaman negara');

            $countries = Country::get();
            foreach ($countries as $country) {
                $this->line('[ * ] Menghapus halaman negara : ' . $country->name);
                CacheSystemDB::forget('country' . $country->slug);
            }
        }

        if ($cacheToDelete == $choices[0] || $cacheToDelete == 'Daftar Kota') {
            $this->line('[ * ] Menghapus cache halaman daftar kota');

            $countries = Country::get();
            foreach ($countries as $country) {
                $this->line('[ * ] Menghapus cache halaman daftar kota di ' . $country->name);
                CacheSystemDB::forget('country' . $country->slug . 'cities');
            }
        }

        if ($cacheToDelete == $choices[0] || $cacheToDelete == 'Daftar Provinsi / State') {
            $this->line('[ * ] Menghapus cache halaman daftar provinsi / state');

            $countries = Country::get();
            foreach ($countries as $country) {
                $this->line('[ * ] Menghapus cache halaman daftar provinsi / state di ' . $country->name);
                CacheSystemDB::forget('country' . $country->slug . 'states');
            }
        }

        if ($cacheToDelete == $choices[0] || $cacheToDelete == 'Daftar Tempat') {
            $this->line('[ * ] Menghapus cache halaman daftar tempat');

            $countries = Country::get();
            $categories = Category::orderBy('name', 'ASC')->whereIn('name', config('scraper.place_types_to_fetch'))->get();
            foreach ($countries as $country) {
                foreach ($categories as $category) {
                    $page = 1;
                    while (CacheSystemDB::get('country' . $country->slug . 'places' . $category->slug . 'page' . $page)) {
                        $this->line('[ * ] Menghapus cache halaman daftar tempat (' . $category->name . ') di ' . $country->name . ' halaman ' . $page);
                        CacheSystemDB::forget('country' . $country->slug . 'places' . $category->slug . 'page' . $page);
                        $page++;
                    }
                }
            }
        }
    }
}
