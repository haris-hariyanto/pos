<?php

namespace App\Console\Commands\Cloner;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Hotel\Hotel;

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
        $segment = 1;
        while (true) {
            $limit = 1000;
            $skip = ($segment - 1) * 1000;

            $hotelsModelClone = new Hotel;
            $hotelsClone = $hotelsModelClone
                ->setConnection('clone')
                ->skip($skip)
                ->take($limit)
                ->get();
            
            if (count($hotelsClone) < 1) {
                return 0;
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
                    'continent' => $hotelClone->continent,
                    'country' => $hotelClone->country,
                    'country_iso_code' => $hotelClone->country_iso_code,
                    'state' => $hotelClone->state,
                    'city' => $hotelClone->city,
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
                    'number_of_reviews' => $hotelClone->number_of_reviews,
                    'rating_average' => $hotelClone->rating_average,
                    'rates_currency' => $hotelClone->rates_currency,
                    'rates_from_exclusive' => $hotelClone->rates_from_exclusive,
                    'accommodation_type' => $hotelClone->ratesaccommodation_type_currency,
                    'photos' => $hotelClone->photos,
                ]);
            }
            
            $segment++;
            // sleep(5);
        }
    }
}
