<?php

return [
    'places_api' => env('GOOGLE_PLACE_API_KEY', ''),
    'place_types_to_fetch' => [
        'airport',
        'tourist_attraction',
        /*
        'embassy',
        'hospital',
        'stadium',
        'subway_station',
        'train_station',
        'university',
        */
    ],
    'queries_translation' => [
        'airport' => 'Bandara',
        'tourist_attraction' => 'Object wisata',
    ],
    'maximum_page' => 2,
    'language' => 'en',
];