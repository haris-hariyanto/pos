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
                    <div class="card tw-shadow hover:tw-shadow-lg h-100">
                        @if (!empty($popularHotel['photos'][0]))
                            @if (config('content.lazy_load'))
                                <img data-src="{{ \App\Helpers\Image::removeQueryParameters($popularHotel['photos'][0]) }}" alt="{{ $popularHotel['name'] }}" class="home-cover-image card-img-top rounded-top lazy">
                            @else
                                <img src="{{ \App\Helpers\Image::removeQueryParameters($popularHotel['photos'][0]) }}" alt="{{ $popularHotel['name'] }}" class="home-cover-image card-img-top rounded-top" loading="lazy">
                            @endif
                        @else
                            <img src="{{ asset('assets/main/images/no-image.png') }}" alt="No image" class="rounded home-cover-image" loading="lazy">
                        @endif
                        <div class="card-body py-1 pb-2 px-2">
                            @if (!empty($popularHotel['star_rating']))
                                <div class="mb-1">
                                    @for ($i = 0; $i < floor($popularHotel['star_rating']); $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                        </svg>
                                    @endfor
                                    @if (str_contains($popularHotel['star_rating'], '.'))
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-half tw-text-orange-400" viewBox="0 0 16 16">
                                            <path d="M5.354 5.119 7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.548.548 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.52.52 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.58.58 0 0 1 .085-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.565.565 0 0 1 .162-.505l2.907-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.001 2.223 8 2.226v9.8z"/>
                                        </svg>
                                    @endif
                                </div>
                            @endif
                            <h3 class="fs-6 mb-1 tw-line-clamp-1">
                                <a href="{{ route('hotel', [$popularHotel['slug']]) }}" class="stretched-link hover:tw-no-underline">{{ $popularHotel['name'] }}</a>
                            </h3>

                            @if ((!empty($popularHotel['city']) || !empty($popularHotel['state'])) && !empty($popularHotel['country']))
                                <div class="my-1 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                                    </svg>
                                    @if (!empty($popularHotel['city']))
                                        <div class="ms-1">{{ $popularHotel['city'] }}, {{ $popularHotel['country'] }}</div>
                                    @else
                                        <div class="ms-1">{{ $popularHotel['state'] }}, {{ $popularHotel['country'] }}</div>
                                    @endif
                                </div>
                            @endif

                            <div class="mt-1">
                                <span class="text-secondary fw-bold">{{ \App\Helpers\Text::price($popularHotel['price'], $popularHotel['rates_currency']) }}</span>
                            </div>
                        </div>
                    </div>
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