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

    public function findID($name, $latitude, $longitude)
    {
        $params = [];
        $params['key'] = $this->key;
        $params['input'] = $name;
        $params['inputtype'] = 'textquery';
        $params['locationbias'] = 'circle:100@' . $latitude . ',' . $longitude;

        $response = Http::get($this->endpoint . 'findplacefromtext/json', $params);

        $response = $response->body();
        $response = json_decode($response, true);

        if ($response['status'] == 'OK') {
            return [
                'success' => true,
                'id' => $response['candidates'][0]['place_id'],
            ];
        }
        else {
            return [
                'success' => false,
                'description' => $response['status'],
            ];
        }
    }

    public function reviews($placeID)
    {
        $params = [];
        $params['place_id'] = $placeID;
        $params['key'] = $this->key;
        $params['fields'] = 'reviews';
        if (config('app.locale') == 'id') {
            $params['language'] = 'id-ID';
        }
        else {
            $params['language'] = 'en';
        }

        $response = Http::get($this->endpoint . 'details/json', $params);

        $response = $response->body();
        $response = json_decode($response, true);

        if ($response['status'] == 'OK') {
            if (!empty($response['result']['reviews'])) {
                return [
                    'success' => true,
                    'reviews' => $response['result']['reviews'],
                ];
            }
            else {
                return [
                    'success' => false,
                    'description' => 'No reviews',
                ];                
            }
        }
        else {
            return [
                'success' => false,
                'description' => $response['status'],
            ];
        }
    }

    public function search($type, $location, $maxPage = 2, $isoCode = false)
    {
        $currentPage = 1;
        $nextPageToken = null;
        $results = [];

        for ($i = $currentPage; $i <= $maxPage; $i++) {
            $params = [];
            $params['key'] = $this->key;
            if (config('scraper.language') == 'id') {
                $params['query'] = str_replace('_', ' ', $type) . ' di ' . $location;
            }
            elseif (config('scraper.language') == 'en') {
                $params['query'] = str_replace('_', ' ', $type) . ' in ' . $location;
            }

            $params['type'] = $type;

            if ($nextPageToken) {
                $params['pagetoken'] = $nextPageToken;
            }
            if ($isoCode) {
                $params['region'] = $isoCode;
            }

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
                        'address' => !empty($result['formatted_address']) ? $result['formatted_address'] : null,
                        'user_ratings_total' => !empty($result['user_ratings_total']) ? $result['user_ratings_total'] : 0,
                    ];
                }

                if (!empty($response['next_page_token'])) {
                    $nextPageToken = $response['next_page_token'];
                    sleep(3);
                }
                else {
                    return [
                        'success' => true,
                        'description' => $response['status'],
                        'results' => $results,
                    ];
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

    // Untuk menambahkan tempat lewat dashboard admin
    public function searchPlaces($query, $isoCode = false)
    {
        $params = [];
        $params['key'] = $this->key;
        $params['query'] = $query;

        if ($isoCode) {
            $params['region'] = $isoCode;
        }

        if (config('app.locale') == 'id') {
            $params['language'] = 'id-ID';
        }
        else {
            $params['language'] = 'en';
        }

        $response = Http::get($this->endpoint . 'textsearch/json', $params);

        $response = $response->body();
        $response = json_decode($response, true);

        if ($response['status'] == 'OK') {
            $results = [];
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
                    'address' => !empty($result['formatted_address']) ? $result['formatted_address'] : null,
                    'user_ratings_total' => !empty($result['user_ratings_total']) ? $result['user_ratings_total'] : 0,
                ];
            }

            return [
                'success' => true,
                'description' => $response['status'],
                'results' => $results,
            ];
        }
        else {
            return [
                'success' => false,
                'description' => $response['status'],
            ];
        }
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