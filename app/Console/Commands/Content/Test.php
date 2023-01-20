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

                    CategoryPlace::firstOrCreate([
                        'category_id' => $categoryInstance->id,
                        'place_id' => $place->id,
                    ]);
                }
            }
        }
    }
}
