<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_place_page_title, [
                '[appname]' => $settings__website_name,
                '[place_name]' => $place['name'],
                '[page]' => $currentPage,
                '[current_url]' => route('place', [$place['slug']]),
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
                <div class="col-12 col-lg-10">
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
                            ])
                        }}
                    </h1>
                    <p class="lead">{{ $place['address'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-1">
        <div class="container pt-4 pb-3">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <div id="map" class="tw-h-96 rounded" data-latitude="{{ $place['latitude'] }}" data-longitude="{{ $place['longitude'] }}"></div>
                </div>
            </div>
        </div>
    
        <div class="container pb-4">
            <div class="row g-2 justify-content-center">
                <div class="col-12 col-lg-10">
                    @forelse ($hotels as $hotel)
                        <div id="hotel{{ $loop->iteration }}" class="tw-absolute -tw-mt-14"></div>
                        <div class="hotelData" 
                            data-latitude="{{ $hotel['hotel']['latitude'] }}" 
                            data-longitude="{{ $hotel['hotel']['longitude'] }}"
                            data-name="{{ $hotel['hotel']['name'] }}"
                            data-number="{{ $loop->iteration }}"
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
                        '<div class="fs-6 mb-1"><a href="#hotel' + hotel.dataset.number + '">' + hotel.dataset.number + '. ' + hotel.dataset.name + '</a></div>' +
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
        </script>
        <script async src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=marker&v=beta&key={{ config('services.google_maps.key_js') }}"></script>
    @endpush
</x-main.layouts.app>