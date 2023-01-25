<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Location\Place;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\HotelPlace;
use App\Helpers\GooglePlaces;

class Hotels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:hotels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape hotels nearby';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function _handle()
    {
        $loop = true;
        while ($loop) {
            $limit = $this->ask('Limit tempat untuk discrape', 10);
            $limitPage = $this->ask('Limit halaman', 2);
            if (is_numeric($limit) && is_numeric($limitPage)) {
                $loop = false;
            }
        }

        for ($i = 1; $i <= $limit; $i++) {
            $place = Place::where('is_hotels_scraped', 'N')
                ->orderBy('user_ratings_total', 'DESC')
                ->first();
            if (!$place) {
                return;
            }

            $place->update([
                'is_hotels_scraped' => 'PROCESS',
            ]);

            $this->info('[ * ] Scraping hotel di sekitar ' . $place->name);

            $googlePlaces = new GooglePlaces();
            $hotels = $googlePlaces->nearbyHotels($place->latitude, $place->longitude, $limitPage);

            $resultCount = 0;
            foreach ($hotels['results'] as $hotel) {
                $latitude = explode('.', $hotel['latitude']);
                $longitude = explode('.', $hotel['longitude']);

                $findHotels = Hotel::select('id', 'name', 'latitude', 'longitude')
                    ->where('latitude', 'like', $latitude[0] . '.' . substr($latitude[1], 0, 2) . '%')
                    ->where('longitude', 'like', $longitude[0] . '.' . substr($longitude[1], 0, 2) . '%')
                    ->where('overview', '<>', '')
                    ->where('number_of_reviews', '>', 0)
                    ->where(function ($query) {
                        $query->whereNotNull('rates_from')
                            ->orWhereNotNull('rates_from_exclusive');
                    })
                    ->whereNotNull('star_rating')
                    ->get();

                foreach ($findHotels as $findHotel) {
                    if (!HotelPlace::where('hotel_id', $findHotel->id)->where('place_id', $place->id)->exists()) {
                        $distanceKM = $this->distance($place->latitude, $place->longitude, $findHotel->latitude, $findHotel->longitude, 'K');

                        HotelPlace::create([
                            'hotel_id' => $findHotel->id,
                            'place_id' => $place->id,
                            'm_distance' => round($distanceKM * 1000, 0),
                        ]);

                        $this->info('[ * ] Hotel ' . $resultCount . ' - ' . $findHotel->name);

                        $resultCount++;
                    }
                }
            }

            $place->update([
                'is_hotels_scraped' => 'Y',
                'hotels_nearby' => $resultCount,
            ]);

            $this->line('--------------------');
        } // [END] for
    }

    public function handle()
    {
        $loop = true;
        while ($loop) {
            $limit = $this->ask('Limit tempat untuk pencarian hotel', 10);
            if (is_numeric($limit)) {
                $loop = false;
            }
        }

        for ($i = 1; $i <= $limit; $i++) {
            $place = Place::where('is_hotels_scraped', 'N')
                ->orderBy('user_ratings_total', 'DESC')
                ->first();
            
            if (!$place) {
                return;
            }

            $place->update([
                'is_hotels_scraped' => 'PROCESS',
            ]);

            $this->info('[ * ] Scraping hotel di sekitar ' . $place->name);

            $latitude = explode('.', $place['latitude']);
            $longitude = explode('.', $place['longitude']);

            $findHotels = Hotel::select('id', 'name', 'latitude', 'longitude')
                ->where('latitude', 'like', $latitude[0] . '.' . substr($latitude[1], 0, 1) . '%')
                ->where('longitude', 'like', $longitude[0] . '.' . substr($longitude[1], 0, 1) . '%')
                ->where('overview', '<>', '')
                ->where('number_of_reviews', '>', 0)
                ->where(function ($query) {
                    $query->whereNotNull('rates_from')
                        ->orWhereNotNull('rates_from_exclusive');
                })
                ->whereNotNull('star_rating')
                ->get();

            $resultCount = 0;
            foreach ($findHotels as $findHotel) {
                if (!HotelPlace::where('hotel_id', $findHotel->id)->where('place_id', $place->id)->exists()) {
                    $distanceKM = $this->distance($place->latitude, $place->longitude, $findHotel->latitude, $findHotel->longitude, 'K');

                    HotelPlace::create([
                        'hotel_id' => $findHotel->id,
                        'place_id' => $place->id,
                        'm_distance' => round($distanceKM * 1000, 0),
                    ]);

                    $this->info('[ * ] Hotel ' . $resultCount . ' - ' . $findHotel->name);

                    $resultCount++;
                }
            }

            $place->update([
                'is_hotels_scraped' => 'Y',
                'hotels_nearby' => $resultCount,
            ]);

            $this->line('--------------------');
        }
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit) {

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
      
        if ($unit == "K") {
          return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
          } else {
              return $miles;
            }
    }
}
