<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_place_page_title, [
                '[appname]' => $settings__website_name,
                '[place_name]' => $place['name'],
                '[page]' => $currentPage,
                '[current_url]' => route('place', [$place['slug']]),
                '[starting_price]' => $lowestPrice,
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($pagesettings_place_meta_data, [
                '[appname]' => $settings__website_name,
                '[place_name]' => $place['name'],
                '[page]' => $currentPage,
                '[current_url]' => route('place', [$place['slug']]),
                '[starting_price]' => $lowestPrice,
            ])
        !!}

        @if ($currentPage == 1)
            <link rel="canonical" href="{{ route('place', [$place['slug']]) }}">
        @else
            <link rel="canonical" href="{{ route('place', [$place['slug'], 'page' => $currentPage]) }}">
        @endif

        {!! $structuredData->render() !!}
    @endpush

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">

            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Breadcrumb -->
                    <div class="mt-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('index') }}">{{ __('Home') }}</a>
                                </li>

                                @if (!empty($place['continent']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('continent', [Str::slug($place['continent']['slug'])]) }}">{{ $place['continent']['name'] }}</a>
                                    </li>
                                @endif

                                @if (!empty($place['country']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('country', [Str::slug($place['country']['slug'])]) }}">{{ $place['country']['name'] }}</a>
                                    </li>
                                @endif

                                <li class="breadcrumb-item active" aria-current="page">{{ $place['name'] }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- [END] Breadcrumb -->

                    <h1 class="fs-2 mb-3">
                        {{ 
                            \App\Helpers\Text::placeholder($pagesettings_place_heading, [
                                '[appname]' => $settings__website_name,
                                '[place_name]' => $place['name'],
                                '[page]' => $currentPage,
                                '[current_url]' => route('place', [$place['slug']]),
                                '[starting_price]' => $lowestPrice,
                            ])
                        }}
                    </h1>
                    <p class="lead">{{ $place['address'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-1">
        <!--
        <div class="container pt-4 pb-3">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div id="map" class="tw-h-96 rounded" data-latitude="{{ $place['latitude'] }}" data-longitude="{{ $place['longitude'] }}"></div>
                </div>
            </div>
        </div>
        -->

        <!-- Map -->
        <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="map" class="h-100 w-100" data-latitude="{{ $place['latitude'] }}" data-longitude="{{ $place['longitude'] }}"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- [END] Map -->
    
        <div class="container py-4">
            <div class="row g-2 justify-content-center" x-data="listing">
                <div class="col-12 col-lg-3">

                    <!-- Filter -->
                    <div class="d-flex flex-wrap d-lg-none">
                        <button type="button" class="btn btn-secondary me-2 mt-2" @click="toggleFilterSection()">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
                                </svg>
                                <div class="ms-2">{{ __('Filter') }}</div>
                            </div>
                        </button>
                        <div class="dropdown me-2 mt-2">
                            <button class="btn btn-secondary dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Sort') }} : <span x-text="currentSortModeText"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="popular" @click="changeSortMode('popular')">{{ __('Most Popular') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="lowest-price" @click="changeSortMode('lowest-price')">{{ __('Lowest Price') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="highest-price" @click="changeSortMode('highest-price')">{{ __('Highest Price') }}</a>
                                </li>
                            </ul>
                        </div>
                        <button class="btn btn-secondary mt-2" type="button" data-bs-toggle="modal" data-bs-target="#mapModal">{{ __('Maps') }}</button>
                    </div>

                    <div class="mt-2 mt-lg-0 card shadow-sm d-none d-lg-block" :class="{ 'd-none': !showFilterSection }" data-base="{{ route('place', [$place['slug']]) }}" x-ref="base">
                        <div class="card-body">
                            <div>
                                <div class="fw-bold mb-1">{{ __('Hotel Star') }}</div>

                                <div class="form-check d-flex align-items-center">
                                    <input type="checkbox" class="form-check-input me-2" id="filterStarUnrated" value="unrated" @change="changeFilter()" x-model="dataFilterStar">
                                    <label for="filterStarUnrated" class="form-check-label">{{ __('Unrated') }}</label>
                                </div>

                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="form-check d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2" id="filterStar{{ $i }}" value="{{ $i }}" @change="changeFilter()" x-model="dataFilterStar">
                                        <label for="filterStar{{ $i }}" class="form-check-label">
                                            @for ($star = 1; $star <= $i; $star++)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                </svg>
                                            @endfor
                                        </label>
                                    </div>
                                @endfor
                            </div>

                            <hr>

                            <div>
                                <div class="fw-bold mb-1">{{ __('Price Range') }}</div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="small text-muted mb-1">{{ __('Min') }}</div>
                                        <div>
                                            <input type="text" class="form-control" x-model="dataFilterMinPrice" @keyup.debounce.1000ms="changeFilter()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted mb-1 text-end">{{ __('Max') }}</div>
                                        <div>
                                            <input type="text" class="form-control" x-model="dataFilterMaxPrice" @keyup.debounce.1000ms="changeFilter()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [END] Filter -->

                </div>
                <div class="col-12 col-lg-9">
                    <!-- Sort -->
                    <div class="mb-2 d-none d-lg-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Sort') }} : <span x-text="currentSortModeText"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="popular" @click="changeSortMode('popular')">{{ __('Most Popular') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="lowest-price" @click="changeSortMode('lowest-price')">{{ __('Lowest Price') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="highest-price" @click="changeSortMode('highest-price')">{{ __('Highest Price') }}</a>
                                </li>
                            </ul>
                        </div>

                        <button class="btn btn-secondary ms-2" type="button" data-bs-toggle="modal" data-bs-target="#mapModal">{{ __('Maps') }}</button>
                    </div>
                    <!-- [END] Sort -->

                    <div x-ref="mainResults">
                        @forelse ($hotels as $hotel)
                            <div id="hotel{{ $loop->iteration }}" class="tw-absolute -tw-mt-14"></div>
                            <div class="hotelData" 
                                data-latitude="{{ $hotel['hotel']['latitude'] }}" 
                                data-longitude="{{ $hotel['hotel']['longitude'] }}"
                                data-name="{{ $hotel['hotel']['name'] }}"
                                data-number="{{ $loop->iteration }}"
                                data-detail="{{ route('hotel', $hotel['hotel']['slug']) }}"
                                data-distance="{{ __('From location: :distance', ['distance' => number_format($hotel['m_distance'] / 1000, 1) . ' KM']) }}"
                                data-rates="{{ __('Rates from') . ': ' . \App\Helpers\Text::price($hotel['hotel']['price'], $hotel['hotel']['rates_currency']) }}"
                            >
                                <x-main.components.contents.hotel :hotel="$hotel['hotel']" :place-and-distance="['place' => $place['name'], 'distance' => $hotel['m_distance']]" />
                            </div>
                        @empty
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p class="mb-0">{{ __('No hotels available.') }}</p>
                                </div>
                            </div>
                        @endforelse
                        <div>
                            {!! $links !!}
                        </div>
                    </div>

                    <div x-show="isLoading">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-show="!isLoading" x-html="results"></div>

                    @if (count($hotels) == 0)
                        <hr>

                        @if (count($altPlaces) > 0)
                            <h2 class="fs-4 mb-3">{{ __('Other Places Near :place', ['place' => $place['name']]) }}</h2>
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($altPlaces as $altPlace)
                                            <div class="col-12 col-lg-6">
                                                <div class="mb-2">
                                                    <div class="tw-line-clamp-1">
                                                        <a href="{{ route('place', [$altPlace['slug']]) }}">{{ $altPlace['name'] }}</a>
                                                    </div>
                                                    <div class="text-muted small tw-line-clamp-1">{{ $altPlace['address'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($altHotels) > 0)
                            <h2 class="fs-4 mb-3">{{ __('Hotel Recommendations') }}</h2>
                            @foreach ($altHotels as $altHotel)
                                <x-main.components.contents.hotel :hotel="$altHotel" />
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scriptsBottom')
        <script>
            const mapContainer = document.getElementById('map');
            const hotels = document.querySelectorAll('.hotelData');

            function initMap() {
                const center = {
                    lat: +mapContainer.dataset.latitude,
                    lng: +mapContainer.dataset.longitude,
                };

                const map = new google.maps.Map(mapContainer, {
                    center: center,
                    zoom: 13,
                    mapId: 'DEMO_MAP_ID',
                });

                const centerPin = new google.maps.marker.PinView({
                    scale: 1.5,
                });

                // Center marker
                const marker = new google.maps.marker.AdvancedMarkerView({
                    position: center,
                    map: map,
                    content: centerPin.element,
                    zIndex: 1000,
                });

                const hotelInfoWindow = new google.maps.InfoWindow({
                    content: "",
                    disableAutoPan: true,
                });

                let index = 100;
                hotels.forEach(hotel => {
                    const pinViewBackground = new google.maps.marker.PinView({
                        background: "#4f46e5",
                        glyphColor: "white",
                        borderColor: "white",
                        scale: 1.5,
                    });

                    const hotelMarker = new google.maps.marker.AdvancedMarkerView({
                        position: {
                            lat: +hotel.dataset.latitude,
                            lng: +hotel.dataset.longitude,
                        },
                        map: map,
                        content: pinViewBackground.element,
                        zIndex: index,
                    });
                    index--;

                    const infoWindowContent = 
                        '<div>' +
                        '<div class="fs-6 mb-1"><a href="' + hotel.dataset.detail + '">' + hotel.dataset.name + '</a></div>' +
                        '<div class="mb-1">' + hotel.dataset.distance + '</div>' +
                        '<div>' + hotel.dataset.rates + '</div>' +
                        '</div>';

                    hotelMarker.addListener('click', () => {
                        hotelInfoWindow.setContent(infoWindowContent);
                        hotelInfoWindow.open(map, hotelMarker);
                    });
                });
            }

            window.initMap = initMap;

            document.addEventListener('alpine:init', () => {
                Alpine.data('listing', () => ({
                    countRequest: 0,
                    dataFilterStar: {{ Js::from(explode(',', request()->query('star', null))) }},
                    dataFilterMinPrice: {!! request()->query('min-price', "''") !!},
                    dataFilterMaxPrice: {!! request()->query('max-price', "''") !!},
                    changeFilter() {
                        this.countRequest++;
                        this.results = '';
                        if (this.$refs.mainResults) {
                            this.$refs.mainResults.remove();
                        }
                        this.isLoading = true;
                        this.getResults();
                    },
                    getResults() {
                        const baseURL = this.$refs.base.dataset.base;
                        const currentRequest = this.countRequest;
                        
                        axios({
                            method: 'GET',
                            url: baseURL,
                            params: {
                                star: this.dataFilterStar.join(','),
                                'min-price': this.dataFilterMinPrice,
                                'max-price': this.dataFilterMaxPrice,
                                'sort-by': this.currentSortMode,
                            },
                        })
                            .then(response => {
                                const responseData = response.data;
                                const responseStatus = response.status;

                                if (responseStatus == '200' && responseData.success == true) {
                                    if (currentRequest == this.countRequest) {
                                        this.isLoading = false;
                                        this.results = responseData.results;
                                    }
                                }
                            });
                    },
                    results: '',
                    isLoading: false,

                    currentSortMode: '{{ request()->query('sort-by', 'popular') }}',
                    get currentSortModeText()
                    {
                        const sortMode = document.querySelectorAll('[data-sort-by="' + this.currentSortMode + '"]');
                        return sortMode[0].innerHTML;
                    },
                    changeSortMode(sortMode)
                    {
                        this.currentSortMode = sortMode;
                        this.changeFilter();
                    },

                    showFilterSection: false,
                    toggleFilterSection() {
                        this.showFilterSection = !this.showFilterSection;
                    },
                }));
            });
        </script>
        <script async src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=marker&v=beta&key={{ config('services.google_maps.key_js') }}"></script>
    @endpush
</x-main.layouts.app>