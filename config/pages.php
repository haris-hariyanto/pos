<?php

return [
    'home' => [
        'name' => 'Home',
        'fields' => [
            'page_title',
            'meta_data',
            'brief_paragraph',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
        ],
    ],
    'hotel' => [
        'name' => 'Hotel',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[hotel_name]' => 'Nama hotel',
            '[hotel_address]' => 'Alamat hotel (bisa kosong)',
            '[hotel_city]' => 'Kota hotel (bisa kosong)',
            '[hotel_state]' => 'State hotel (bisa kosong)',
            '[hotel_country]' => 'Negara',
            '[hotel_image]' => 'Gambar hotel',
        ],
    ],
    'place' => [
        'name' => 'Place',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[place_name]' => 'Nama tempat',
            '[total_hotels]' => 'Jumlah hotel yang ditemukan',
            '[page]' => 'Nomor halaman',
        ],
    ],
    'continent' => [
        'name' => 'Continent',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[continent_name]' => 'Nama benua',
        ],
    ],
    'country' => [
        'name' => 'Country',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[country_name]' => 'Nama negara',
        ],
    ],
    'country_states' => [
        'name' => 'Country (States)',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[country_name]' => 'Nama negara',
        ],
    ],
    'country_cities' => [
        'name' => 'Country (Cities)',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[country_name]' => 'Nama negara',
        ],
    ],
    'country_places' => [
        'name' => 'Country (Places)',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[country_name]' => 'Nama negara',
            '[place_category]' => 'Kategori tempat',
            '[page]' => 'Nomor halaman',
        ],
    ],
    'city' => [
        'name' => 'City',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[country_name]' => 'Nama negara',
            '[city_name]' => 'Nama kota',
            '[page]' => 'Nomor halaman',
        ],
    ],
    'state' => [
        'name' => 'State',
        'fields' => [
            'heading',
            'page_title',
            'meta_data',
        ],
        'variables' => [
            '[appname]' => 'Nama website',
            '[current_url]' => 'URL halaman',
            '[country_name]' => 'Nama negara',
            '[state_name]' => 'Nama state',
            '[page]' => 'Nomor halaman',
        ],
    ],
];