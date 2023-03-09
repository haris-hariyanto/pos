<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Chain;
use App\Models\Hotel\Brand;
use App\Models\Hotel\HotelPlace;
use App\Models\Hotel\Review;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\City;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\Location\CategoryPlace;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheSystemDB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

    public function handle()
    {
        $reviews = Review::select('hotel_id')->distinct()->get();
        $reviews = $reviews->pluck('hotel_id')->toArray();
        $hotels = Hotel::where('is_reviews_scraped', 'Y')->where('number_of_reviews', 0)
            ->orderBy('number_of_reviews', 'DESC')
            ->get();
        $skip = true;
        foreach ($hotels as $hotel) {
            if ($hotel->id == '1766591') {
                $skip = false;
            }
            if ($skip) {
                continue;
            }
            $this->line($hotel->id);
        }
        /*
        $hotels = Hotel::whereRaw('total_views <> weekly_views')->get();
        foreach ($hotels as $hotel) {
            $hotel->update([
                'total_views' => $hotel->weekly_views,
            ]);
        }
        */
    }

    public function __handle()
    {
        /*
        CacheSystemDB::forgetWithTags([
            '[country:',
            '[category:',
        ]);

        return 0;
        */
        $caches = DB::table('page_caches')->get();
        foreach ($caches as $cache) {
            if (!Storage::exists('caches/' . $cache->key . '.json')) {
                Storage::put('caches/' . $cache->key . '.json', $cache->value);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function _handle()
    {
        Place::truncate();
        Category::truncate();
        CategoryPlace::truncate();
        // Clone places
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * $limit;

            $placesModelClone = new Place;
            $placesClone = $placesModelClone
                ->setConnection('clone')
                // ->with('categories')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($placesClone) < 1) {
                break;
            }

            foreach ($placesClone as $placeClone) {
                $this->line('[ * ] Place ID ' . $placeClone->id . ' : ' . $placeClone->name);
                $place = Place::create([
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

                foreach ($placeClone->categories()->get() as $categoryClone) {
                    $category = Category::firstOrCreate(
                        ['slug' => $categoryClone['slug']],
                        ['name' => $categoryClone['name']],
                    );

                    CategoryPlace::firstOrCreate(
                        ['place_id' => $place->id, 'category_id' => $category->id],
                        ['country' => $placeClone->country, 'continent' => $placeClone->continent]
                    );
                }
            }

            $segment++;
        }
        // [END] Clone places

        /*

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
                $sourcePlace = new Place;
                $place_ = $sourcePlace->setConnection('clone')
                    ->where('id', $placeCategoryClone->place_id)
                    ->first();
                $place = Place::where('gmaps_id', $place_->gmaps_id)
                    ->first();

                $sourceCategory = new Category;
                $category_ = $sourceCategory->setConnection('clone')
                    ->where('id', $placeCategoryClone->category_id)
                    ->first();
                $category = Category::where('slug', $category_->slug)
                    ->first();
                    
                CategoryPlace::create([
                    'place_id' => $place->id,
                    'category_id' => $category->id,
                    'country' => $placeCategoryClone->country,
                    'continent' => $placeCategoryClone->continent,
                ]);
            }

            $segment++;
        }
        // [END] Clone category place
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
