<?php

namespace App\Console\Commands\Scraper;

use Illuminate\Console\Command;
use App\Models\Hotel\Hotel;
use App\Models\Hotel\Review;
use App\Helpers\GooglePlaces;
use App\Helpers\CacheSystemDB;
use App\Helpers\Text;
use Illuminate\Support\Facades\Cache;

class HotelReview extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:reviews';

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
        $this->line('Scrape Review Hotel');

        $hotelsTotal = Hotel::count();
        $hotelsWithReviewsScraped = Hotel::where('is_reviews_scraped', 'Y')->count();
        $hotelsWithReviewsNotScraped = $hotelsTotal - $hotelsWithReviewsScraped;

        $this->line('Jumlah hotel : ' . $hotelsTotal);
        $this->line('Hotel dengan review sudah discrape : ' . $hotelsWithReviewsScraped);
        $this->line('Hotel dengan review belum discrape : ' . $hotelsWithReviewsNotScraped);

        $limitHotels = $this->ask('Limit Hotel', 1000);

        $loop = true;
        $i = 1;
        while ($loop) {
            $hotels = Hotel::where('is_reviews_scraped', 'N')
                ->take(100)
                ->orderBy('number_of_reviews', 'DESC')
                ->get();
            
            foreach ($hotels as $hotel) {

                $this->line('[ * ] Scrape review : ' . $i . ' / ' . $limitHotels);
                $this->line('[ * ] Nama hotel : ' . $hotel->name);
                
                try {
                    CacheSystemDB::forget('hotel' . $hotel->slug);
                }
                catch (\Illuminate\Database\QueryException $queryException) {
                    $this->error('[ * ] Database error');
                    $this->line('[ * ] Sleep : 3 detik');
                    $this->line('--------------------');
                    sleep(3);
                    continue;
                }
                
                $hotel->update([
                    'is_reviews_scraped' => 'PROCESS',
                ]);
    
                $this->line('[ * ] Mencari ID hotel di Google Place');
                $failedToFindID = false;
                $reviewsToSave = [];
    
                if (!empty($hotel->latitude) && !empty($hotel->longitude)) {
                    $googlePlaces = new GooglePlaces();

                    try {
                        $findPlaceID = $googlePlaces->findID($hotel->name, $hotel->latitude, $hotel->longitude);
                    }
                    catch (\Illuminate\Http\Client\RequestException $exception) {
                        $this->error('[ * ] Error');
                        continue;
                    }
    
                    if ($findPlaceID['success']) {
                        $placeID = $findPlaceID['id'];
    
                        $this->line('[ * ] ID ditemukan : ' . $placeID);
    
                        try {
                            $getReviews = $googlePlaces->reviews($placeID);
                        }
                        catch (\Illuminate\Http\Client\RequestException $exception) {
                            $this->error('[ * ] Error');
                            continue;
                        }
    
                        if ($getReviews['success'] && count($getReviews['reviews']) > 0) {
                            $reviews = $getReviews['reviews'];
    
                            foreach ($reviews as $review) {
                                $reviewToSave = [];
                                $reviewToSave['name'] = $review['author_name'];
                                $reviewToSave['rating'] = $review['rating'];
                                $reviewToSave['time'] = $review['time'];
                                $reviewToSave['review'] = Text::plain($review['text']);
                                $reviewToSave['is_accepted'] = 'Y';
                                $reviewToSave['source'] = 'google';

                                if (!empty($reviewToSave['review'])) {
                                    $reviewsToSave[] = $reviewToSave;
                                }
                            }
                            $this->line('[ * ] Menyimpan review');
                        }
                        else {
                            $this->line('[ * ] Tidak ada review');
                        }
                    }
                    else {
                        $additionalData = [];
                        $additionalData['reviews'] = $findPlaceID['description'];
                        $additionalData = json_encode($additionalData);

                        $this->error('[ * ] API Error : ' . $findPlaceID['description']);
                    }
                }
                else {
                    $failedToFindID = true;
                }
    
                if ($failedToFindID) {
                    $additionalData = [];
                    $additionalData['reviews'] = 'ID not found';
                    $additionalData = json_encode($additionalData);

                    $this->line('[ * ] ID hotel tidak ditemukan');
                }

                $hotel->update([
                    'is_reviews_scraped' => 'Y',
                    'additional_data' => isset($additionalData) ? $additionalData : null,
                ]);

                $prepareData = [];
                foreach ($reviewsToSave as $reviewToSave) {
                    $prepareData[] = new Review($reviewToSave);
                }
                $hotel->reviews()->saveMany($prepareData);
                Cache::forget('reviewscount');
    
                $this->line('--------------------');

                $i++;
                if ($i > $limitHotels) {
                    break;
                }
            } // [END] foreach

            if ($i > $limitHotels) {
                $loop = false;
            }
        }
    }
}
