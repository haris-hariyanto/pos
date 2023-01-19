<?php

namespace App\Console\Commands\Cloner;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Chain;
use App\Models\Hotel\Brand;
use App\Models\Hotel\HotelPlace;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Country;
use App\Models\Location\Continent;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\ScrapeHistory;

class CloneDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clone';

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
        // Clone hotels
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $hotelsModelClone = new Hotel;
            $hotelsClone = $hotelsModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($hotelsClone) < 1) {
                break;
            }

            foreach ($hotelsClone as $hotelClone) {
                $this->line('[ * ] Hotel ID ' . $hotelClone->id . ' : ' . $hotelClone->name);
                Hotel::create([
                    'slug' => $hotelClone->slug,
                    'chain' => $hotelClone->chain,
                    'brand' => $hotelClone->brand,
                    'name' => $hotelClone->name,
                    'formerly_name' => $hotelClone->formerly_name,
                    'translated_name' => $hotelClone->translated_name,
                    'address_line_1' => $hotelClone->address_line_1,
                    'address_line_2' => $hotelClone->address_line_2,
                    'zipcode' => $hotelClone->zipcode,
                    'city' => $hotelClone->city,
                    'state' => $hotelClone->state,
                    'country' => $hotelClone->country,
                    'country_iso_code' => $hotelClone->country_iso_code,
                    'continent' => $hotelClone->continent,
                    'star_rating' => $hotelClone->star_rating,
                    'longitude' => $hotelClone->longitude,
                    'latitude' => $hotelClone->latitude,
                    'url' => $hotelClone->url,
                    'check_in' => $hotelClone->check_in,
                    'check_out' => $hotelClone->check_out,
                    'number_of_rooms' => $hotelClone->number_of_rooms,
                    'number_of_floors' => $hotelClone->number_of_floors,
                    'year_opened' => $hotelClone->year_opened,
                    'year_renovated' => $hotelClone->year_renovated,
                    'overview' => $hotelClone->overview,
                    'rates_from' => $hotelClone->rates_from,
                    'number_of_reviews' => $hotelClone->number_of_reviews,
                    'rating_average' => $hotelClone->rating_average,
                    'rates_currency' => $hotelClone->rates_currency,
                    'rates_from_exclusive' => $hotelClone->rates_from_exclusive,
                    'accommodation_type' => $hotelClone->ratesaccommodation_type_currency,
                    'photos' => $hotelClone->photos,
                    'additional_data' => $hotelClone->additional_data
                ]);
            }
            
            $segment++;
        }
        // [END] Clone hotels

        // Clone chains
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $chainsModelClone = new Chain;
            $chainsClone = $chainsModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($chainsClone) < 1) {
                break;
            }

            foreach ($chainsClone as $chainClone) {
                $this->line('[ * ] Chain ID ' . $chainClone->id . ' : ' . $chainClone->name);
                Chain::create([
                    'slug' => $chainClone->slug,
                    'name' => $chainClone->name,
                ]);
            }

            $segment++;
        }
        // [END] Clone chains

        // Clone brands
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $brandsModelClone = new Brand;
            $brandsClone = $brandsModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($brandsClone) < 1) {
                break;
            }

            foreach ($brandsClone as $brandClone) {
                $this->line('[ * ] Brand ID ' . $brandClone->id . ' : ' . $brandClone->name);
                Brand::create([
                    'slug' => $brandClone->slug,
                    'name' => $brandClone->name,
                ]);
            }
            
            $segment++;
        }
        // [END] Clone brands

        // Clone cities
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $citiesModelClone = new City;
            $citiesClone = $citiesModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($citiesClone) < 1) {
                break;
            }

            foreach ($citiesClone as $cityClone) {
                $this->line('[ * ] City ID ' . $cityClone->id . ' : ' . $cityClone->name);
                City::create([
                    'slug' => $cityClone->slug,
                    'name' => $cityClone->name,
                    'state' => $cityClone->state,
                    'country' => $cityClone->country,
                    'continent' => $cityClone->continent,
                ]);
            }

            $segment++;
        }
        // [END] Clone cities

        // Clone states
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $statesModelClone = new State;
            $statesClone = $statesModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($statesClone) < 1) {
                break;
            }

            foreach ($statesClone as $stateClone) {
                $this->line('[ * ] State ID ' . $stateClone->id . ' : ' . $stateClone->name);
                State::create([
                    'slug' => $stateClone->slug,
                    'name' => $stateClone->name,
                    'country' => $stateClone->country,
                    'continent' => $stateClone->continent,
                ]);
            }

            $segment++;
        }
        // [END] Clone states

        // Clone countries
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $countriesModelClone = new Country;
            $countriesClone = $countriesModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($countriesClone) < 1) {
                break;
            }

            foreach ($countriesClone as $countryClone) {
                $this->line('[ * ] Country ID ' . $countryClone->id . ' : ' . $countryClone->name);
                Country::create([
                    'slug' => $countryClone->slug,
                    'name' => $countryClone->name,
                    'iso_code' => $countryClone->iso_code,
                    'continent' => $countryClone->continent,
                    'is_scraped' => $countryClone->is_scraped,
                ]);
            }

            $segment++;
        }
        // [END] Clone countries

        // Clone continent
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $continentsModelClone = new Continent;
            $continentsClone = $continentsModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($continentsClone) < 1) {
                break;
            }

            foreach ($continentsClone as $continentClone) {
                $this->line('[ * ] Continent ID ' . $continentClone->id . ' : ' . $continentClone->name);
                Continent::create([
                    'slug' => $continentClone->slug,
                    'name' => $continentClone->name,
                ]);
            }

            $segment++;
        }
        // [END] Clone continents

        // Clone places
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $placesModelClone = new Place;
            $placesClone = $placesModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($placesClone) < 1) {
                break;
            }

            foreach ($placesClone as $placeClone) {
                $this->line('[ * ] Place ID ' . $placeClone->id . ' : ' . $placeClone->name);
                Place::create([
                    'slug' => $placeClone->slug,
                    'name' => $placeClone->name,
                    'type' => $placeClone->type,
                    'address' => $placeClone->address,
                    'longitude' => $placeClone->longitude,
                    'latitude' => $placeClone->latitude,
                    'city' => $placeClone->city,
                    'state' => $placeClone->state,
                    'country' => $placeClone->country,
                    'continent' => $placeClone->continent,
                    'gmaps_id' => $placeClone->gmaps_id,
                    'category' => $placeClone->category,
                    'category_id' => $placeClone->category_id,
                    'is_hotels_scraped' => $placeClone->is_hotels_scraped,
                    'hotels_nearby' => $placeClone->hotels_nearby,
                    'user_ratings_total' => $placeClone->user_ratings_total,
                    'additional_data' => $placeClone->additional_data,
                ]);
            }

            $segment++;
        }
        // [END] Clone places

        // Clone categories
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $categoriesModelClone = new Category;
            $categoriesClone = $categoriesModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($categoriesClone) < 1) {
                break;
            }

            foreach ($categoriesClone as $categoryClone) {
                Category::create([
                    'slug' => $categoryClone['slug'],
                    'name' => $categoryClone['name'],
                ]);
            }

            $segment++;
        }
        // [END] Clone categories

        // Clone hotel place
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $hotelsPlaceModelClone = new HotelPlace;
            $hotelsPlaceClone = $hotelsPlaceModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($hotelsPlaceClone) < 1) {
                break;
            }

            foreach ($hotelsPlaceClone as $hotelClone) {
                HotelPlace::create([
                    'hotel_id' => $hotelClone->hotel_id,
                    'place_id' => $hotelClone->place_id,
                    'm_distance' => $hotelClone->m_distance,
                    'additional_data' => $hotelClone->additional_data,
                ]);
            }

            $segment++;
        }
        // [END] Clone hotel place

        // Clone scrape histories
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $scrapeHistoriesModelClone = new ScrapeHistory;
            $scrapeHistoriesClone = $scrapeHistoriesModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();

            if (count($scrapeHistoriesClone) < 1) {
                break;
            }

            foreach ($scrapeHistoriesClone as $scrapeHistoryClone) {
                ScrapeHistory::create([
                    'location_id' => $scrapeHistoryClone->location_id,
                    'google_place_type' => $scrapeHistoryClone->google_place_type,
                ]);
            }

            $segment++;
        }
        // [END] Clone scrape histories
    }
}
