<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_home_page_title, [
                '[appname]' => $settings__website_name,
                '[current_url]' => route('index'),
            ]) 
        }}
    </x-slot>

    @push('metaData')
        {!! 
            \App\Helpers\Text::placeholder($pagesettings_home_meta_data, [
                '[appname]' => $settings__website_name,
                '[current_url]' => route('index'),
            ]) 
        !!}

        <link rel="canonical" href="{{ route('index') }}">
    @endpush

    <div class="bg-white shadow-sm">
        <div class="container py-5 px-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-9">
                    <h1>{{ $settings__website_name }}</h1>
                    <p>{{ \App\Helpers\Text::placeholder($pagesettings_home_brief_paragraph, ['[appname]' => $settings__website_name, '[current_url]' => route('index'),]) }}</p>
                    <div x-data="autocomplete" x-ref="base" data-base="{{ route('search.autocomplete') }}">
                        <!-- Search bar for big screen -->
                        <form action="{{ route('search') }}" method="GET" class="d-none d-lg-block">
                            <div class="input-group mb-3">
                                <select name="mode" class="form-select" style="flex: 0 1 fit-content;">
                                    <option value="findHotels">{{ __('Find Hotels') }}</option>
                                    <option value="findPlaces">{{ __('Find Places') }}</option>
                                </select>
                                <input type="text" class="form-control" placeholder="{{ __('Enter an address or property') }}" name="q" data-search="true" x-model="searchQuery" @keyup.debounce="getAutocomplete()">
                                <button class="btn btn-primary px-5" type="submit">{{ __('Search') }}</button>
                            </div>
                        </form>
                        <!-- [END] Search bar for big screen -->

                        <!-- Search bar for small screen -->
                        <form action="{{ route('search') }}" method="GET" class="d-block d-lg-none" x-data="{ searchMode: 'findHotels' }">
                            <div class="mb-2">
                                <div class="mb-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-5" :class="{ 'active' : searchMode == 'findHotels' }" @click="searchMode = 'findHotels'">{{ __('Hotels') }}</button>
                                    <button type="button" class="btn btn-outline-primary btn-sm rounded-5" :class="{ 'active' : searchMode == 'findPlaces' }" @click="searchMode = 'findPlaces'">{{ __('Places') }}</button>
                                </div>

                                <input type="hidden" name="mode" x-model="searchMode">

                                <div class="input-group">
                                    <!--
                                    <select name="mode" class="form-select" style="width: 33%;">
                                        <option value="findHotels">{{ __('Hotels') }}</option>
                                        <option value="findPlaces">{{ __('Places') }}</option>
                                    </select>
                                    -->
                                    <input type="text" class="form-control rounded-end" style="width: 66%;" placeholder="{{ __('Enter an address or property') }}" name="q" data-search="true" x-model="searchQuery" @keyup.debounce="getAutocomplete()">
                                </div>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary px-5" type="submit">{{ __('Search') }}</button>
                            </div>
                        </form>
                        <!-- [END] Search bar form small screen -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <h2 class="fs-3 mb-3">{{ __('Find Hotels' )}}</h2>

        <div class="row g-2 tw-justify-center">
            @foreach ($continents as $continent)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                @if (config('content.lazy_load'))
                                    <img data-src="{{ !empty($homeCoverImages[$continent['slug']]) ? $homeCoverImages[$continent['slug']] : config('content.default_cover') }}" alt="{{ $continent['name'] }}" class="rounded home-cover-image lazy">
                                @else
                                    <img src="{{ !empty($homeCoverImages[$continent['slug']]) ? $homeCoverImages[$continent['slug']] : config('content.default_cover') }}" alt="{{ $continent['name'] }}" class="rounded home-cover-image" loading="lazy">
                                @endif
                            </div>
                            <h3 class="fs-5 mb-2">{{ $continent['name'] }}</h3>
                            <div class="row">
                                @foreach ($continent['countries'] as $country)
                                    <div class="col-6 tw-line-clamp-1 mb-1">
                                        <a href="{{ route('country', ['country' => $country['slug']]) }}">{{ $country['name'] }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer text-end py-2">
                            <a href="{{ route('continent', ['continent' => $continent['slug']]) }}" class="btn btn-outline-primary btn-sm">{{ __('More Countries') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container py-4">
        <h2 class="fs-3 mb-3">{{ __('Popular Hotels') }}</h2>

        <div class="row g-2 tw-justify-center">
            @foreach ($popularHotels as $popularHotel)
                <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                    <x-main.components.contents.hotel-small :hotel="$popularHotel" />
                </div>
            @endforeach
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-2">
            <div class="col-12 col-lg-6">
                <h2 class="fs-3 mb-3">{{ __('Popular Places') }}</h2>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($popularPlaces as $popularPlace)
                                <div class="col-12 col-lg-6">
                                    <div class="mb-2">
                                        <div class="tw-line-clamp-1"><a href="{{ route('place', [$popularPlace['slug']]) }}">{{ $popularPlace['name'] }}</a></div>
                                        <div class="tw-line-clamp-1">{{ $popularPlace['country'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <h2 class="fs-3 mt-2 mt-lg-0 mb-3">{{ __('Popular Cities') }}</h2>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($popularCities as $popularCity)
                                <div class="col-12 col-lg-6">
                                    <div class="mb-2">
                                        <div class="tw-line-clamp-1"><a href="{{ route('hotel.location', [config('content.location_term_city'), $popularCity['slug']]) }}">{{ $popularCity['name'] }}</a></div>
                                        <div class="tw-line-clamp-1">{{ $popularCity['country'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>