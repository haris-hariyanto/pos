<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Helpers\CacheSystemDB;
use Illuminate\Support\Facades\Http;
use App\Models\Hotel\Hotel;
use App\Models\Location\Place;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Category;
use Illuminate\Support\Facades\Cache;

class GenerateRefreshCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:cache';

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
        $refreshCache = $this->confirm('Refresh cache halaman yang sudah memiliki cache', false);

        $choices = [
            'Semua halaman',
            'Halaman Awal',
            'Hotel',
            'Tempat',
            'Benua',
            'Negara',
            'Negara (Daftar State)',
            'Negara (Daftar Kota)',
            'Negara (Daftar Tempat)',
            'Kota',
            'Provinsi / State',
        ];

        $cacheToDelete = $this->choice('Pilih halaman untuk dicache', $choices, 0);

        // Halaman Awal
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[1]) {
            $route = route('index');
            $createCache = false;

            if (CacheSystemDB::get('homepage')) {
                if ($refreshCache) {
                    $this->info('[ * ] Menghapus cache : ' . $route);
                    CacheSystemDB::forgetSync('homepage');

                    $createCache = true;
                }
            }
            else {
                $createCache = true;
            }

            if ($createCache) {
                $this->info('[ * ] Membuat cache : ' . $route);
                Http::retry(5, 1000)->get($route);
            }
        }
        // [END] Halaman Awal

        // Hotel
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[2]) {
            $hotels = Hotel::orderBy('total_views', 'DESC')
                ->orderBy('number_of_reviews', 'DESC')
                ->get();
            foreach ($hotels as $hotel) {
                $route = route('hotel', [$hotel->slug]);
                $createCache = false;
                $cacheKey = 'hotel' . $hotel->slug;

                if (CacheSystemDB::get($cacheKey)) {
                    if ($refreshCache) {
                        $this->info('[ * ] Menghapus cache : ' . $route);
                        CacheSystemDB::forgetSync($cacheKey);

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    dispatch(function () use ($route) {
                        Http::retry(5, 1000)->get($route);
                    });
                }
            } // [END] foreach
        }
        // [END] Hotel

        // Tempat
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[3]) {
            $places = Place::orderBy('total_views', 'DESC')
                ->orderBy('user_ratings_total', 'DESC')
                ->get();
            foreach ($places as $place) {
                $route = route('place', [$place->slug]);
                $createCache = false;
                $cacheKey = 'place' . $place->slug . 'page';

                if (CacheSystemDB::get($cacheKey . '1')) {
                    if ($refreshCache) {
                        // Delete lowest price cache
                        Cache::forget('place' . $place->slug . 'lowest-price');
                        // [END] Delete lowest price cache

                        $page = 1;
                        $loop = true;
                        while ($loop) {
                            if (CacheSystemDB::get($cacheKey . $page)) {
                                $routeWithPage = route('place', [$place->slug, 'page' => $page]);
                                $this->info('[ * ] Menghapus cache : ' . $routeWithPage);
                                CacheSystemDB::forgetSync($cacheKey . $page);

                                // Cache if no hotels found
                                CacheSystemDB::forgetSync('alternative-places' . $place->slug);
                                // [END] Cache if no hotels found

                                $page++;
                            }
                            else {
                                $loop = false;
                            }
                        }

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    dispatch(function () use ($route) {
                        Http::retry(5, 1000)->get($route);
                    });
                }
            }
        }
        // [END] Tempat

        // Benua
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[4]) {
            $continents = Continent::get();
            foreach ($continents as $continent) {
                $route = route('continent', [$continent->slug]);
                $createCache = false;
                $cacheKey = 'continent' . $continent->slug;

                if (CacheSystemDB::get($cacheKey)) {
                    if ($refreshCache) {
                        $this->info('[ * ] Menghapus cache : ' . $route);
                        CacheSystemDB::forgetSync($cacheKey);

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    Http::retry(5, 1000)->get($route);
                }
            }
        }
        // [END] Benua

        // Negara
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[5]) {
            $countries = Country::get();
            foreach ($countries as $country) {
                $route = route('country', [$country->slug]);
                $createCache = false;
                $cacheKey = 'country' . $country->slug;

                if (CacheSystemDB::get($cacheKey)) {
                    if ($refreshCache) {
                        $this->info('[ * ] Menghapus cache : ' . $route);
                        CacheSystemDB::forgetSync($cacheKey);

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    Http::retry(5, 1000)->get($route);
                }
            }
        }
        // [END] Negara

        // Negara (Daftar State)
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[6]) {
            $countries = Country::get();
            foreach ($countries as $country) {
                $route = route('country.states', [$country->slug]);
                $createCache = false;
                $cacheKey = 'country' . $country->slug . 'states';

                if (CacheSystemDB::get($cacheKey)) {
                    if ($refreshCache) {
                        $this->info('[ * ] Menghapus cache : ' . $route);
                        CacheSystemDB::forgetSync($cacheKey);

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    Http::retry(5, 1000)->get($route);
                }
            }
        }
        // [END] Negara (Daftar State)

        // Negara (Daftar Kota)
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[7]) {
            $countries = Country::get();
            foreach ($countries as $country) {
                $route = route('country.cities', [$country->slug]);
                $createCache = false;
                $cacheKey = 'country' . $country->slug . 'cities';

                if (CacheSystemDB::get($cacheKey)) {
                    if ($refreshCache) {
                        $this->info('[ * ] Menghapus cache : ' . $route);
                        CacheSystemDB::forgetSync($cacheKey);

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    Http::retry(5, 1000)->get($route);
                }
            }
        }
        // [END] Negara (Daftar Kota)

        // Negara (Daftar Tempat)
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[8]) {
            $countries = Country::get();
            foreach ($countries as $country) {
                $categories = Category::whereIn('name', config('scraper.place_types_to_fetch'))
                    ->get();
                foreach ($categories as $category) {
                    $route = route('country.places', [$country->slug, $category->slug]);
                    $createCache = false;
                    $cacheKey = 'country' . $country->slug . 'places' . $category->slug . 'page';

                    if (CacheSystemDB::get($cacheKey . '1')) {
                        if ($refreshCache) {
                            $page = 1;
                            $loop = true;
                            while ($loop) {
                                if (CacheSystemDB::get($cacheKey . $page)) {
                                    $routeWithPage = route('country.places', [$country->slug, $category->slug, 'page' => $page]);
                                    $this->info('[ * ] Menghapus cache : ' . $routeWithPage);
                                    CacheSystemDB::forgetSync($cacheKey . $page);
                                }
                                else {
                                    $loop = false;
                                }
                            }

                            $createCache = true;
                        }
                    }
                    else {
                        $createCache = true;
                    }

                    if ($createCache) {
                        $this->info('[ * ] Membuat cache : ' . $route);
                        Http::retry(5, 1000)->get($route);
                    }
                }
            }
        }
        // [END] Negara (Daftar Tempat)

        // Kota
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[9]) {
            $cities = City::get();
            foreach ($cities as $city) {
                $type = config('content.location_term_city');
                $route = route('hotel.location', [$type, $city->slug]);
                $createCache = false;
                $cacheKey = 'location' . $type . $city->slug . 'page';

                if (CacheSystemDB::get($cacheKey . '1')) {
                    if ($refreshCache) {
                        // Delete lowest price cache
                        Cache::forget('location' . $type . $city->slug . 'lowest-price');
                        // [END] Delete lowest price cache

                        $page = 1;
                        $loop = true;
                        while ($loop) {
                            if (CacheSystemDB::get($cacheKey . $page)) {
                                $routeWithPage = route('hotel.location', [$type, $city->slug, 'page' => $page]);
                                $this->info('[ * ] Menghapus cache : ' . $routeWithPage);
                                CacheSystemDB::forgetSync($cacheKey . $page);

                                $page++;
                            }
                            else {
                                $loop = false;
                            }
                        }

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    Http::retry(5, 1000)->get($route);
                }
            }
        }
        // [END] Kota

        // Provinsi / State
        if ($cacheToDelete == $choices[0] || $cacheToDelete == $choices[10]) {
            $states = State::get();
            foreach ($states as $state) {
                $type = config('content.location_term_state');
                $route = route('hotel.location', [$type, $state->slug]);
                $createCache = false;
                $cacheKey = 'location' . $type . $state->slug . 'page';

                if (CacheSystemDB::get($cacheKey . '1')) {
                    if ($refreshCache) {
                        // Delete lowest price cache
                        Cache::forget('location' . $type . $state->slug . 'lowest-price');
                        // [END] Delete lowest price cache

                        $page = 1;
                        $loop = true;
                        while ($loop) {
                            if (CacheSystemDB::get($cacheKey . $page)) {
                                $routeWithPage = route('hotel.location', [$type, $state->slug, 'page' => $page]);
                                $this->info('[ * ] Menghapus cache : ' . $routeWithPage);
                                CacheSystemDB::forgetSync($cacheKey . $page);

                                $page++;
                            }
                            else {
                                $loop = false;
                            }
                        }

                        $createCache = true;
                    }
                }
                else {
                    $createCache = true;
                }

                if ($createCache) {
                    $this->info('[ * ] Membuat cache : ' . $route);
                    Http::retry(5, 1000)->get($route);
                }
            }
        }
        // [END] Provinsi / State
    }
}
