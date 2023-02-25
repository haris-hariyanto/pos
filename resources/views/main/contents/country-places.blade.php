<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_country_places_page_title, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $country['name'],
                '[place_category]'=> $category['name'],
                '[page]' => $currentPage,
                '[current_url]' => route('country.places', [$country['slug'], $category['slug']]),
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($pagesettings_country_places_meta_data, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $country['name'],
                '[place_category]'=> $category['name'],
                '[page]' => $currentPage,
                '[current_url]' => route('country.places', [$country['slug'], $category['slug']]),
            ])
        !!}

        @if ($currentPage == 1)
            <link rel="canonical" href="{{ route('country.places', [$country['slug'], $category['slug']]) }}">
        @else
            <link rel="canonical" href="{{ route('country.places', [$country['slug'], $category['slug'], 'page' => $currentPage]) }}">
        @endif
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
                        @if (!empty($country['continent']) && !empty($country['continent']['name']))
                            <li class="breadcrumb-item">
                                <a href="{{ route('continent', [$country['continent']['slug']]) }}">{{ $country['continent']['name'] }}</a>
                            </li>
                        @endif
                        <li class="breadcrumb-item">
                            <a href="{{ route('country', [$country['slug']]) }}">{{ $country['name'] }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __(':place in :country', ['place' => $category['name'], 'country' => $country['name']]) }}</li>
                    </ol>
                </nav>
            </div>
            <!-- [END] Breadcrumb -->

            <h1 class="fs-2 mb-3">
                {{ 
                    \App\Helpers\Text::placeholder($pagesettings_country_places_heading, [
                        '[appname]' => $settings__website_name,
                        '[country_name]' => $country['name'],
                        '[place_category]'=> $category['name'],
                        '[page]' => $currentPage,
                        '[current_url]' => route('country.places', [$country['slug'], $category['slug']]),
                    ])
                }}
            </h1>

        </div>
    </div>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                @if (count($places) > 0)
                    <div class="row">
                        @foreach ($places as $place)
                            @if (!empty($place['place']))
                                <div class="col-12 col-lg-4">
                                    <div class="mb-2">
                                        <div class="tw-line-clamp-1">
                                            <a href="{{ route('place', [$place['place']['slug']]) }}">{{ $place['place']['name'] }}</a>
                                        </div>
                                        <div class="text-muted small tw-line-clamp-1">
                                            {{ $place['place']['address'] }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <div>
                        <p class="mb-0">{{ __('No places available.') }}</p>
                    </div>
                @endif
            </div>
        </div>
        @if (count($places) > 0)
            <div class="my-2">
                {!! $links !!}
            </div>
        @endif
    </div>

    <div class="container pb-4">
        <h2 class="fs-3 mb-3">{{ __('Popular Hotels') }}</h2>

        <div class="row g-2 tw-justify-center">
            @foreach ($hotels as $hotel)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                    <x-main.components.contents.hotel-small :hotel="$hotel" :location="$hotel['nearby_place']" />
                </div>
            @endforeach
        </div>
    </div>
</x-main.layouts.app>