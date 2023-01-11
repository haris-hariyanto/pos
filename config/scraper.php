<?php

return [
    'places_api' => env('GOOGLE_PLACE_API_KEY', ''),
    'place_types_to_fetch' => [
        'airport',
        /*
        'embassy',
        'hospital',
        'stadium',
        'subway_station',
        'tourist_attraction',
        'train_station',
        'university',
        */
    ],
    'maximum_page' => 2,
    'language' => 'id',
];