<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class GooglePlaces
{
    private $endpoint = 'https://maps.googleapis.com/maps/api/place/';
    private $key;

    public function __construct()
    {
        $this->key = config('scraper.places_api');
    }

    public function search($type, $location, $maxPage = 2)
    {
        $currentPage = 1;
        if (config('app.locale')) {
            $searchQuery = config('scraper.queries_translation.' . $type) . ' di ' . $location;
        }
        else {
            $searchQuery = config('scraper.queries_translation.' . $type) . ' in ' . $location;
        }
        $nextPageToken = null;
        $results = [];

        for ($i = $currentPage; $i <= $maxPage; $i++) {
            $params = [];
            $params['key'] = $this->key;
            $params['query'] = $searchQuery;
            $params['type'] = $type;
            if ($nextPageToken) {
                $params['pagetoken'] = $nextPageToken;
            }
            $params['language'] = config('scraper.language');

            $response = Http::get($this->endpoint . 'textsearch/json', $params);

            $response = $response->body();
            $response = json_decode($response, true);

            if ($response['status'] == 'OK') {
                foreach ($response['results'] as $result) {
                    $results[] = [
                        'name' => $result['name'],
                        'latitude' => $result['geometry']['location']['lat'],
                        'longitude' => $result['geometry']['location']['lng'],
                        'viewport' => [
                            'northeast' => [
                                'latitude' => $result['geometry']['viewport']['northeast']['lat'],
                                'longitude' => $result['geometry']['viewport']['northeast']['lng'],
                            ],
                            'southwest' => [
                                'latitude' => $result['geometry']['viewport']['southwest']['lat'],
                                'longitude' => $result['geometry']['viewport']['southwest']['lng'],
                            ],
                        ],
                        'id' => $result['place_id'],
                        'types' => $result['types'],
                        'address' => $result['formatted_address'],
                        'user_ratings_total' => !empty($result['user_ratings_total']) ? $result['user_ratings_total'] : 0,
                    ];
                }

                if (!empty($response['next_page_token'])) {
                    $nextPageToken = $response['next_page_token'];
                    sleep(3);
                }
                else {
                    break;
                }
            }
            else {
                return [
                    'success' => false,
                    'description' => $response['status'],
                ];
            }
        }

        return [
            'success' => true,
            'description' => $response['status'],
            'results' => $results,
        ];
    }

    public function nearbyHotels($latitude, $longitude, $maxPage = 2)
    {
        $currentPage = 1;
        $nextPageToken = null;
        $results = [];

        for ($i = $currentPage; $i <= $maxPage; $i++) {
            $params = [];
            $params['key'] = $this->key;
            $params['location'] = $latitude . ',' . $longitude;
            $params['type'] = 'lodging';
            $params['radius'] = '50000';
            if ($nextPageToken) {
                $params['pagetoken'] = $nextPageToken;
            }

            $response = Http::get($this->endpoint . 'nearbysearch/json', $params);

            $response = $response->body();
            $response = json_decode($response, true);

            if ($response['status'] == 'OK') {
                foreach ($response['results'] as $result) {
                    $results[] = [
                        'name' => $result['name'],
                        'latitude' => $result['geometry']['location']['lat'],
                        'longitude' => $result['geometry']['location']['lng'],
                    ];
                }

                if (!empty($response['next_page_token'])) {
                    $nextPageToken = $response['next_page_token'];
                    sleep(3);
                }
                else {
                    break;
                }
            }
            else {
                return [
                    'success' => false,
                    'description' => $response['status'],
                ];
            }
        } // [END] for

        return [
            'success' => true,
            'description' => $response['status'],
            'results' => $results,
        ];
    }
}