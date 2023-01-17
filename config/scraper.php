<?php

return [
    'places_api' => env('GOOGLE_PLACE_API_KEY', ''),
    'place_types_to_fetch' => [
        'airport',
        'tourist_attraction',
        'embassy',
        'hospital',
        'stadium',
        'subway_station',
        'train_station',
        'university',
    ],
    'maximum_page' => 3,
    'language' => 'en',
];