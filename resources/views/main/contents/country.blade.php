<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_country_page_title, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $country['name'],
                '[current_url]' => route('country', [$country['slug']]),
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($pagesettings_country_meta_data, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $country['name'],
                '[current_url]' => route('country', [$country['slug']]),
            ])
        !!}

        <link rel="canonical" href="{{ route('country', [$country['slug']]) }}">
    @endpush

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">

            <!-- Breadcrumb -->
            <div class="mt-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index') }}">{{ __('Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('continent', [$country['continent']['slug']]) }}">{{ $country['continent']['name'] }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $country['name'] }}</li>
                    </ol>
                </nav>
            </div>
            <!-- [END] Breadcrumb -->

            <h1 class="fs-2 mb-3">
                {{
                    \App\Helpers\Text::placeholder($pagesettings_country_heading, [
                        '[appname]' => $settings__website_name,
                        '[country_name]' => $country['name'],
                        '[current_url]' => route('country', [$country['slug']]),
                    ])
                }}
            </h1>
        </div>
    </div>

    <div class="container py-4">
        <iframe 
            src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&q={{ urlencode($country['name']) }}"
            class="border-0 w-100 tw-h-96 rounded shadow-sm"
            frameborder="0"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen>
        </iframe>
    </div>

    <div class="container pb-4">
        <div class="row g-2">
            <div class="col-12 col-lg-6 d-flex flex-column">
                <h2 class="fs-3 mb-3"><a href="{{ route('country.cities', [$country['slug']]) }}">{{ __('Cities in :country', ['country' => $country['name']]) }}</a></h2>
                <!-- Cities list -->
                <div class="card shadow-sm flex-grow-1">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($cities as $city)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="mb-2 tw-line-clamp-1">
                                        <a href="{{ route('hotel.location', ['city', $city['slug']]) }}">{{ $city['name'] }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-end">
                    <a href="{{ route('country.cities', [$country['slug']]) }}" class="btn btn-outline-primary btn-sm">{{ __('All Cities') }} &raquo;</a>
                </div>
                <!-- [END] Cities list -->
            </div>
            <div class="col-12 col-lg-6 d-flex flex-column">
                <h2 class="fs-3 mb-3 mt-2 mt-lg-0"><a href="{{ route('country.states', [$country['slug']]) }}">{{ __('States in :country', ['country' => $country['name']]) }}</a></h2>
                <!-- States list -->
                <div class="card shadow-sm flex-grow-1">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($states as $state)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="mb-2 tw-line-clamp-1">
                                        <a href="{{ route('hotel.location', ['state', $state['slug']]) }}">{{ $state['name'] }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="mt-2 text-end">
                    <a href="{{ route('country.states', [$country['slug']]) }}" class="btn btn-outline-primary btn-sm">{{ __('All States') }} &raquo;</a>
                </div>
                <!-- [END] States list -->
            </div>
        </div>
    </div>

    @if (count($places) > 0)
        <div class="container pb-4">
            <h2 class="fs-3 mb-3">{{ __('Popular Places in :country', ['country' => $country['name']]) }}</h2>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <!-- Places list -->
                    <div class="row g-2">
                        @foreach ($places as $place)
                            <div class="col-12 col-lg-4">
                                <div class="mb-2">
                                    <div class="tw-line-clamp-1">
                                        <a href="{{ route('place', [$place['slug']]) }}">{{ $place['name'] }}</a>
                                    </div>
                                    <div class="text-muted small tw-line-clamp-1">
                                        {{ $place['address'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- [END] Places list -->
                    <hr>
                    @foreach ($categories as $category)
                        <a href="{{ route('country.places', [$country['slug'], $category['slug']]) }}" class="btn btn-outline-secondary shadow-sm mb-1">{{ __(ucwords(str_replace('_', ' ', $category->name))) }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="container pb-4">
        <h2 class="fs-3 mb-3">{{ __('Best Hotels in :country', ['country' => $country['name']]) }}</h2>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-2">
                    @foreach ($bestHotels as $bestHotel)
                        <div class="col-12 col-lg-4">
                            <div class="mb-3">
                                <div class="tw-line-clamp-1">
                                    <a href="{{ route('hotel', [$bestHotel['slug']]) }}">{{ $bestHotel['name'] }}</a>
                                </div>
                                <div class="mb-1">
                                    @for ($i = 0; $i < $bestHotel['star_rating']; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <div class="tw-line-clamp-1">
                                    <div class="small text-muted">{{ $bestHotel['address_line_1'] . ', ' . $bestHotel['city'] }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>