<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute harus diterima.',
    'accepted_if' => 'The :attribute must be accepted when :other is :value.',
    'active_url' => 'URL :attribute tidak valid.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => ':attribute hanya bisa berisi huruf.',
    'alpha_dash' => ':attribute hanya bisa berisi huruf, angka, tanda pisah, dan underscore.',
    'alpha_num' => ':attribute hanya bisa berisi huruf dan angka.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'array' => ':attribute harus berjumlah antara :min sampai :max.',
        'file' => 'Ukuran file :attribute harus di antara :min sampai :max kilobytes.',
        'numeric' => ':attribute harus di antara :min sampai :max.',
        'string' => 'Panjang :attribute harus di antara :min sampai :max karakter.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => ':attribute tidak cocok.',
    'current_password' => 'Password salah.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'declined' => 'The :attribute must be declined.',
    'declined_if' => 'The :attribute must be declined when :other is :value.',
    'different' => ':attribute dan :other harus berbeda.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'doesnt_end_with' => 'The :attribute may not end with one of the following: :values.',
    'doesnt_start_with' => 'The :attribute may not start with one of the following: :values.',
    'email' => ':attribute harus berupa alamat email.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'enum' => 'The selected :attribute is invalid.',
    'exists' => ':attribute tidak valid.',
    'file' => ':attribute harus berupa file.',
    'filled' => ':attribute harus diisi.',
    'gt' => [
        'array' => 'The :attribute must have more than :value items.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'numeric' => 'The :attribute must be greater than :value.',
        'string' => 'The :attribute must be greater than :value characters.',
    ],
    'gte' => [
        'array' => 'The :attribute must have :value items or more.',
        'file' => 'The :attribute must be greater than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be greater than or equal to :value.',
        'string' => 'The :attribute must be greater than or equal to :value characters.',
    ],
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute tidak valid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => ':attribute harus berupa integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'array' => 'The :attribute must have less than :value items.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'numeric' => 'The :attribute must be less than :value.',
        'string' => 'The :attribute must be less than :value characters.',
    ],
    'lte' => [
        'array' => 'The :attribute must not have more than :value items.',
        'file' => 'The :attribute must be less than or equal to :value kilobytes.',
        'numeric' => 'The :attribute must be less than or equal to :value.',
        'string' => 'The :attribute must be less than or equal to :value characters.',
    ],
    'mac_address' => 'The :attribute must be a valid MAC address.',
    'max' => [
        'array' => ':attribute tidak boleh lebih dari :max.',
        'file' => 'Ukuran file :attribute tidak boleh lebih dari :max kilobytes.',
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'string' => 'Panjang :attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits' => 'The :attribute must not have more than :max digits.',
    'mimes' => ':attribute harus berupa file dengan tipe: :values.',
    'mimetypes' => ':attribute harus berupa file dengan tipe: :values.',
    'min' => [
        'array' => ':attribute minimal harus :min.',
        'file' => 'Ukuran file :attribute minimal harus :min kilobytes.',
        'numeric' => ':attribute minimal harus :min.',
        'string' => 'Panjang :attribute minimal harus :min karakter.',
    ],
    'min_digits' => 'The :attribute must have at least :min digits.',
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => ':attribute tidak valid.',
    'not_regex' => 'Format :attribute tidak valid.',
    'numeric' => ':attribute harus berupa angka.',
    'password' => [
        'letters' => ':attribute harus mengandung minimal satu huruf.',
        'mixed' => ':attribute harus mengandung minimal satu huruf besar dan satu huruf kecil.',
        'numbers' => ':attribute harus mengandung minimal satu angka.',
        'symbols' => ':attribute harus mengandung minimal satu simbol.',
        'uncompromised' => ':attribute sudah terlalu umum. Gunakan :attribute lain.',
    ],
    'present' => 'The :attribute field must be present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'prohibits' => 'The :attribute field prohibits :other from being present.',
    'regex' => 'Format :attribute tidak valid.',
    'required' => ':attribute tidak boleh kosong.',
    'required_array_keys' => 'The :attribute field must contain entries for: :values.',
    'required_if' => ':attribute tidak boleh kosong.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => ':attribute tidak boleh kosong.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => ':attribute atau :values harus diisi salah satu.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'same' => ':attribute dan :other harus cocok.',
    'size' => [
        'array' => ':attribute harus berjumlah :size.',
        'file' => 'Ukuran file :attribute harus :size kilobytes.',
        'numeric' => ':attribute harus :size.',
        'string' => 'Panjang :attribute harus :size karakter.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => ':attribute harus berupa string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => ':attribute sudah ada.',
    'uploaded' => ':attribute gagal diupload.',
    'url' => ':attribute harus berupa URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'g-recaptcha-response' => 'reCAPTCHA',
        'email' => 'Email',
        'password' => 'Password',
        'current_password' => 'Password saat ini',
        'token' => 'Token',
        'username' => 'Username',
        'name' => 'Nama',
        'group_id' => 'Group',
        'avatar' => 'Avatar',
        'page_title' => 'Judul halaman',
        'page_content' => 'Konten',
        'page_slug' => 'URL',
        'page_description' => 'Meta Description',
        'subject' => 'Subject',
        'message' => 'Pesan',
        'settings__website_name' => 'Nama Website',
        'settings__header_script' => 'Script',
        'settings__footer_script' => 'Script',

        'pagesettings_home_page_title' => 'Judul Halaman',
        'pagesettings_home_meta_data' => 'Meta Data',
        'pagesettings_hotel_page_title' => 'Judul Halaman',
        'pagesettings_hotel_meta_data' => 'Meta Data',
        'pagesettings_place_page_title' => 'Judul Halaman',
        'pagesettings_place_meta_data' => 'Meta Data',
        'pagesettings_continent_page_title' => 'Judul Halaman',
        'pagesettings_continent_meta_data' => 'Meta Data',
        'pagesettings_country_page_title' => 'Judul Halaman',
        'pagesettings_country_meta_data' => 'Meta Data',
        'pagesettings_country_states_page_title' => 'Judul Halaman',
        'pagesettings_country_states_meta_data' => 'Meta Data',
        'pagesettings_country_cities_page_title' => 'Judul Halaman',
        'pagesettings_country_cities_meta_data' => 'Meta Data',
        'pagesettings_country_places_page_title' => 'Judul Halaman',
        'pagesettings_country_places_meta_data' => 'Meta Data',
        'pagesettings_city_page_title' => 'Judul Halaman',
        'pagesettings_city_meta_data' => 'Meta Data',
        'pagesettings_state_page_title' => 'Judul Halaman',
        'pagesettings_state_meta_data' => 'Meta Data',

        'formerly_name' => 'Nama Sebelumnya',
        'translated_name' => 'Nama Diterjemahkan',
        'star_rating' => 'Bintang Hotel',
        'url' => 'URL',
        'price' => 'Harga',
        'rates_currency' => 'Mata Uang',
        'overview' => 'Overview',
        'brand' => 'Brand Hotel',
        'chain' => 'Jaringan Hotel',
        'address_line_1' => 'Address Line 1',
        'address_line_2' => 'Address Line 2',
        'zipcode' => 'Kode Pos',
        'check_in' => 'Check In',
        'check_out' => 'Check Out',
        'number_of_floors' => 'Jumlah Lantai',
        'number_of_rooms' => 'Jumlah Kamar',
        'year_opened' => 'Tahun Dibuka',
        'year_renovated' => 'Tahun Direnovasi',
        'accommodation_type' => 'Jenis Akomodasi',
        'photos_hotlinks.*' => 'Foto',
        'photos_uploads.*' => 'Foto',
        'country' => 'Negara',
        'city' => 'Kota',
        'state' => 'Provinsi / Negara Bagian',
        'longitude' => 'Longitude',
        'latitude' => 'Latitude',
        'review' => 'Review',
        'rating' => 'Rating',
        'reply' => 'Balasan',
    ],

];
