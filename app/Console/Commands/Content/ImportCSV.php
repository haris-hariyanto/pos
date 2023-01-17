<?php

namespace App\Console\Commands\Content;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use App\Models\Hotel\Chain;
use App\Models\Hotel\Brand;
use App\Models\Hotel\Hotel;
use App\Models\Location\Continent;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\City;
use App\Models\Source;
use Illuminate\Support\Str;

class ImportCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert CSV records to database items';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $csvFiles = Storage::files('csv');

        foreach ($csvFiles as $csvFile) {
            $path = storage_path('app/' . $csvFile);
            Source::firstOrCreate(
                ['name' => $path]
            );
        }
        

        $source = Source::where('is_saved', 'N')->first();
        if (!$source) {
            return 0;
        }
        $source->update([
            'is_saved' => 'PROCESS'
        ]);
        $path = $source->name;

        $csv = Reader::createFromPath($path);
        $records = $csv->getRecords();

        $firstID = Hotel::orderBy('id', 'DESC')->first();
        if (!$firstID) {
            $firstID = 1;
        }
        else {
            $firstID = $firstID->id;
            $firstID++;
        }

        foreach ($records as $record) {
            // Skip if data incomplete
            if (!isset($record[40])) {
                continue;
            }

            /**
             * Hotel data
             */
            $chainID = trim($record[1]);
            $chainName = trim($record[2]);
            $brandID = trim($record[3]);
            $brandName = trim($record[4]);
            $hotelName = trim($record[5]);
            $hotelFormerlyName = trim($record[6]);
            $hotelTranslatedName = trim($record[7]);
            $addressLine1 = trim($record[8]);
            $addressLine2 = trim($record[9]);
            $zipCode = trim($record[10]);
            $city = trim($record[11]);
            $state = trim($record[12]);
            $country = trim($record[13]);
            $countryISOCode = trim($record[14]);
            $continent = trim($record[33]);
            $starRating = trim($record[15]);
            $longitude = trim($record[16]);
            $latitude = trim($record[17]);
            $url = trim($record[18]);
            $checkIn = trim($record[19]);
            $checkOut = trim($record[20]);
            $numberOfRooms = trim($record[21]);
            $numberOfFloors = trim($record[22]);
            $yearOpened = trim($record[23]);
            $yearRenovated = trim($record[24]);
            $photos = [
                trim($record[25]),
                trim($record[26]),
                trim($record[27]),
                trim($record[28]),
                trim($record[29]),
            ];
            $overview = trim($record[30]);
            $ratesFrom = trim($record[31]);
            $numberOfReviews = trim($record[36]);
            $ratingAverages = trim($record[37]);
            $ratesCurrency = trim($record[38]);
            $ratesFromExclusive = trim($record[39]);
            $accommodationType = trim($record[40]);

            $hotelSlug = $firstID . '-' . Str::slug($hotelName);

            $photoToSave = [];
            foreach ($photos as $photo) {
                $photoToSave[] = $photo;
            }

            $hotel = Hotel::create([
                'slug' => $hotelSlug,
                'chain' => !empty($chainID) ? $chainName : null,
                'brand' => !empty($brandID) ? $brandName : null,
                'name' => $hotelName,
                'formerly_name' => !empty($hotelFormerlyName) ? $hotelFormerlyName : null,
                'translated_name' => !empty($hotelTranslatedName) ? $hotelTranslatedName : null,
                'address_line_1' => !empty($addressLine1) ? $addressLine1 : null,
                'address_line_2' => !empty($addressLine2) ? $addressLine2 : null,
                'zipcode' => !empty($zipCode) ? $zipCode : null,
                'continent' => $continent,
                'country' => $country,
                'country_iso_code' => $countryISOCode,
                'state' => $state,
                'city' => $city,
                'star_rating' => !empty($starRating) ? $starRating : null,
                'longitude' => !empty($longitude) ? $longitude : null,
                'latitude' => !empty($latitude) ? $latitude : null,
                'url' => !empty($url) ? $url : null,
                'check_in' => !empty($checkIn) ? $checkIn : null,
                'check_out' => !empty($checkOut) ? $checkOut : null,
                'number_of_rooms' => !empty($numberOfRooms) ? $numberOfRooms : null,
                'number_of_floors' => !empty($numberOfFloors) ? $numberOfFloors : null,
                'year_opened' => !empty($yearOpened) ? $yearOpened : null,
                'year_renovated' => !empty($yearRenovated) ? $yearRenovated : null,
                'overview' => $overview,
                'rates_from' => !empty($ratesFrom) ? $ratesFrom : null,
                'number_of_reviews' => !empty($numberOfReviews) && is_numeric($numberOfReviews) ? $numberOfReviews : 0,
                'rating_average' => !empty($ratingAverages) ? $ratingAverages : null,
                'rates_currency' => !empty($ratesCurrency) ? $ratesCurrency : null,
                'rates_from_exclusive' => !empty($ratesFromExclusive) ? $ratesFromExclusive : null,
                'accommodation_type' => !empty($accommodationType) ? $accommodationType : null,
                'photos' => json_encode($photoToSave),
            ]);
            $firstID = $hotel->id;
            $firstID++;

            $this->info('[ * ] Import Data Hotel');
            $this->info('[ * ] Nama hotel : ' . $hotelName);

            $this->line('--------------------');
        }

        $source->update([
            'is_saved' => 'Y'
        ]);
    }
}
