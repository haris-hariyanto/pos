<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_continent_page_title, [
                '[appname]' => $settings__website_name,
                '[continent_name]' => $continent['name'],
                '[current_url]' => route('continent', [$continent['slug']]),
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($pagesettings_continent_meta_data, [
                '[appname]' => $settings__website_name,
                '[continent_name]' => $continent['name'],
                '[current_url]' => route('continent', [$continent['slug']]),
            ])
        !!}

        <link rel="canonical" href="{{ route('continent', [$continent['slug']]) }}">
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
                        <li class="breadcrumb-item active" aria-current="page">{{ $continent['name'] }}</li>
                    </ol>
                </nav>
            </div>
            <!-- [END] Breadcrumb -->
            
            <h1 class="fs-2 mb-3">
                {{
                    \App\Helpers\Text::placeholder($pagesettings_continent_heading, [
                        '[appname]' => $settings__website_name,
                        '[continent_name]' => $continent['name'],
                        '[current_url]' => route('continent', [$continent['slug']]),
                    ])
                }}
            </h1>
        </div>
    </div>

    <div class="container py-4">
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <div class="row">
                    @foreach ($continent['countries'] as $country)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if (config('content.lazy_load'))
                                            <img data-src="https://flagsapi.com/{{ $country['iso_code'] }}/flat/24.png" alt="" class="lazy" width="24" height="24">
                                        @else
                                            <img src="https://flagsapi.com/{{ $country['iso_code'] }}/flat/24.png" alt="" loading="lazy" width="24" height="24">
                                        @endif
                                    </div>
                                    <div class="tw-line-clamp-1">
                                        <a href="{{ route('country', [$country['slug']]) }}">{{ $country['name'] }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-4">
        <h2 class="fs-3 mb-3">{{ __('Popular Cities in :continent', ['continent' => $continent['name']]) }}</h2>
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <!-- Cities list -->
                <div class="row g-2">
                    @foreach ($cities as $city)
                        <div class="col-12 col-md-6 col-lg-3 col-xl-2">
                            <div class="mb-2">
                                <div class="tw-line-clamp-1">
                                    <a href="{{ route('hotel.location', [config('content.location_term_city'), $city['slug']]) }}">{{ $city['name'] }}</a>
                                </div>
                                <div class="text-muted small tw-line-clamp-1">
                                    {{ $city['country'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- [END] Cities list -->
            </div>
        </div>
    </div>

    @foreach ($places as $category => $placesList)
        <div class="container pb-4">
            <h2 class="fs-3 mb-3">{{ __('Popular :place_category in :continent', ['place_category' => __(ucwords(str_replace('_', ' ', $category))), 'continent' => $continent['name']]) }}</h2>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <!-- Places list -->
                    <div class="row g-2">
                        @foreach ($placesList as $place)
                            <div class="col-12 col-lg-4">
                                <div class="mb-2">
                                    <div class="tw-line-clamp-1">
                                        <a href="{{ route('place', [$place['slug']]) }}">{{ $place['name'] }}</a>
                                    </div>
                                    <div class="text-muted small tw-line-clamp-1">
                                        {{ $place['country'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- [END] Places list -->
                </div>
            </div>
        </div>
    @endforeach
</x-main.layouts.app>