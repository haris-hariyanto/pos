<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Location\City;
use App\Models\Location\State;
use App\Models\Location\Country;
use App\Models\Location\Place;
use App\Models\Location\Category;
use App\Models\ScrapeHistory;
use App\Helpers\GooglePlaces;
use Illuminate\Support\Str;

class Places extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:places';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape places using Google Map Place Api';

    private $location;
    private $locationType;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scrapingMode = [
            'Lokasi tertentu',
            'Random',
        ];
        $mode = $this->choice('Mode scraping', $scrapingMode);

        $locationTypes = ['city', 'state', 'country'];
        // $locationType = $this->choice('Location type', $locationTypes, 0);
        $locationType = $locationTypes[2];

        if ($mode == $scrapingMode[0]) {
            $locations = $this->ask('Masukkan nama lokasi (pisahkan dengan tanda koma)');
            $locations = explode(',', $locations);

            foreach ($locations as $location) {
                $location = trim($location);

                if ($locationType == $locationTypes[0]) {
                    $location = City::where('name', 'like', $location . '%')
                        ->where('is_scraped', 'N')
                        ->first();
                    $this->locationType = 'city';
                }
                elseif ($locationType == $locationTypes[1]) {
                    $location = State::where('name', 'like', $location . '%')
                        ->where('is_scraped', 'N')
                        ->first();
                    $this->locationType = 'state';
                }
                elseif ($locationType == $locationTypes[2]) {
                    $location = Country::where('name', 'like', $location . '%')
                        ->where('is_scraped', 'N')
                        ->first();
                    $this->locationType = 'country';
                }

                if ($location) {
                    $this->line('[ * ] Lokasi : ' . $location->name);
                    $this->location = $location;
                    $location->update(['is_scraped' => 'PROCESS']);
                    $this->scrapePlaces();
                    $location->update(['is_scraped' => 'Y']);
                }
                else {
                    $this->info('[ * ] Lokasi tidak ada atau sudah discrape');
                }

                $this->line('--------------------');
            } // [END] foreach
        }
        else {
            $limit = $this->ask('Limit scraping', 10);
            for ($i = 1; $i <= $limit; $i++) {
                if ($locationType == $locationTypes[0]) {
                    $location = City::where('is_scraped', 'N')
                        ->first();
                    $this->locationType = 'city';
                }
                elseif ($locationType == $locationTypes[1]) {
                    $location = State::where('is_scraped', 'N')
                        ->first();
                    $this->locationType = 'state';
                }
                elseif ($locationType == $locationTypes[2]) {
                    $location = Country::where('is_scraped', 'N')
                        ->first();
                    $this->locationType = 'country';
                }

                if ($location) {
                    $this->info('[ * ] Lokasi : ' . $location->name);
                    $this->location = $location;
                    $location->update(['is_scraped' => 'PROCESS']);
                    $this->scrapePlaces();
                    $location->update(['is_scraped' => 'Y']);
                }
                else {
                    break;
                }
                $this->line('--------------------');
            } // [END] for
        }
    }

    private function scrapePlaces()
    {
        $location = $this->location;
        $locationType = $this->locationType;
        $placeTypesToFetch = config('scraper.place_types_to_fetch');
        foreach ($placeTypesToFetch as $type) {
            $scrapeID = $locationType . '.' . $location->id;
            $isScraped = ScrapeHistory::where('location_id', $scrapeID)
                ->where('google_place_type', $type)
                ->exists();

            $this->info('[ * ] Scraping ' . $type . ' di ' . $location->name);
            if ($isScraped) {
                $this->info('[ * ] Data sudah ada');
            }
            else {
                $googlePlaces = new GooglePlaces();
                $places = $googlePlaces->search($type, $location->name, config('scraper.maximum_page'));

                if (!$places['success']) {
                    $this->error('[ * ] API Error : ' . $places['description']);
                    return 0;
                }

                foreach ($places['results'] as $place) {
                    if (!Place::where('gmaps_id', $place['id'])->exists()) {

                        $placeSlug = $this->createUniqueSlug($place['name']);

                        $dataToSave = [];
                        $dataToSave['slug'] = $placeSlug;
                        $dataToSave['name'] = $place['name'];
                        $dataToSave['type'] = 'PLACE';
                        $dataToSave['address'] = $place['address'];
                        $dataToSave['longitude'] = $place['longitude'];
                        $dataToSave['latitude'] = $place['latitude'];

                        if ($locationType == 'country') {
                            if (!empty($location->id)) {
                                $dataToSave['country_id'] = $location->id;
                            }
    
                            if (!empty($location->name)) {
                                $dataToSave['country'] = $location->name;
                            }
                        }

                        if (!empty($location->continent_id)) {
                            $dataToSave['continent_id'] = $location->continent_id;
                        }

                        if (!empty($location->continent)) {
                            $dataToSave['continent'] = $location->continent;
                        }

                        $dataToSave['gmaps_id'] = $place['id'];
                        $dataToSave['category'] = $place['types'][0];

                        $category = Category::firstOrCreate([
                            'name' => $place['types'][0],
                            'slug' => Str::slug($place['types'][0]),
                        ]);
                        $dataToSave['category_id'] = $category->id;

                        $additionalData = [];
                        $additionalData['viewport'] = $place['viewport'];
                        $additionalData['types'] = $place['types'];
                        $dataToSave['additional_data'] = json_encode($additionalData);

                        $dataToSave['user_ratings_total'] = $place['user_ratings_total'];

                        Place::create($dataToSave);

                    }
                }

                ScrapeHistory::create([
                    'location_id' => $scrapeID,
                    'google_place_type' => $type,
                ]);
            }
        }
    }

    private function createUniqueSlug($name)
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $tailID = 1;
        $loop = true;
        while ($loop) {
            $slugCount = Place::where('slug', $slug)->count();
            if ($slugCount == 0) {
                $loop = false;
            }
            else {
                $slug = $baseSlug . '-' . $tailID;
                $tailID++;
            }
        }

        if (empty(trim($slug))) {
            $lastRecord = Place::orderBy('id', 'desc')->first();
            $slug = $lastRecord->id + 1;
        }

        return $slug;
    }
}
