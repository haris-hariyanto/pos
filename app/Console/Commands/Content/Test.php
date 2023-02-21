<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Chain;
use App\Models\Hotel\Brand;
use App\Models\Hotel\HotelPlace;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\City;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\Location\CategoryPlace;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:test';

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
        Place::truncate();
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
        Category::truncate();
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

        // Clone category place
        CategoryPlace::truncate();
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $placeCategoriesModelClone = new CategoryPlace;
            $placeCategoriesClone = $placeCategoriesModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($placeCategoriesClone) < 1) {
                break;
            }

            foreach ($placeCategoriesClone as $placeCategoryClone) {
                CategoryPlace::create([
                    'place_id' => $placeCategoryClone->place_id,
                    'category_id' => $placeCategoryClone->category_id,
                    'country' => $placeCategoryClone->country,
                    'continent' => $placeCategoryClone->continent,
                ]);
            }

            $segment++;
        }
        // [END] Clone category place
        /*
        $places = Place::get();
        foreach ($places as $place) {
            $additionalData = $place->additional_data;
            $additionalData = json_decode($additionalData, true);

            if (!empty($additionalData['types'])) {
                $categories = $additionalData['types'];
                foreach ($categories as $category) {
                    $categoryInstance = Category::firstOrCreate([
                        'slug' => Str::slug($category),
                        'name' => $category,
                    ]);

                    $categoryPlace = CategoryPlace::updateOrCreate(
                        [
                            'category_id' => $categoryInstance->id,
                            'place_id' => $place->id,
                        ],
                        [
                            'country' => $place->country,
                            'continent' => $place->continent,
                        ]
                    );
                }
            }
        }
        */
    }
}
