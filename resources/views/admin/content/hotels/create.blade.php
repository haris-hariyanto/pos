<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Add Hotel') }}</x-slot>

    @if (session('success'))
        <x-admin.components.alert>
            {{ session('success') }}
        </x-admin.components.alert>
    @endif

    @if (session('error'))
        <x-admin.components.alert type="danger">
            {{ session('error') }}
        </x-admin.components.alert>
    @endif

    <form action="{{ route('admin.hotels.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-12 col-lg-6">

                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.input-text name="name" :label="__('Name')" :value="old('name')" />

                        <div class="row">
                            <div class="col-6">
                                <x-admin.forms.input-text name="formerly_name" :label="__('Formerly Name')" :value="old('formerly_name')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="translated_name" :label="__('Translated Name')" :value="old('translated_name')" />
                            </div>
                        </div>

                        <hr>

                        <div>
                            <div class="font-weight-bold">{{ __('Hotel Star') }}</div>
                            <div>
                                <div class="row g-0">
                                    @php
                                        $stars = ['1', '1.5', '2', '2.5', '3', '3.5', '4', '4.5', '5']; 
                                    @endphp
                                    @foreach ($stars as $star)
                                        <div class="col-3">
                                            <x-admin.forms.radio :label="$star" name="star_rating" :value="$star" :selected="$star == old('star_rating')" />
                                        </div>
                                    @endforeach
                                    <div class="col-3">
                                        <x-admin.forms.radio :label="__('Unrated')" name="star_rating" value="unrated" :selected="old('star_rating') == 'unrated'" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <x-admin.forms.input-text name="url" :label="__('URL')" :value="old('url')" />

                        <div class="row g-0">
                            <div class="col-6">
                                <x-admin.forms.input-text name="price" :label="__('Rates From')" :value="old('price')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="rates_currency" :label="__('Rates Currency')" :value="old('rates_currency')" />
                            </div>
                        </div>

                        <x-admin.forms.textarea name="overview" :label="__('Overview')">{{ old('overview') }}</x-admin.forms.textarea>

                        <hr>

                        <div class="row g-0">
                            <div class="col-6">
                                <x-admin.forms.input-text name="brand" :label="__('Hotel Brand')" :value="old('brand')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="chain" :label="__('Hotel Chain')" :value="old('chain')" />
                            </div>
                        </div>

                        <hr>

                        <x-admin.forms.textarea name="address_line_1" :label="__('Address Line 1')" rows="2">{{ old('address_line_1') }}</x-admin.forms.textarea>
                        <x-admin.forms.textarea name="address_line_2" :label="__('Address Line 2')" rows="2">{{ old('address_line_2') }}</x-admin.forms.textarea>
                        <x-admin.forms.input-text name="zipcode" :label="__('ZIP Code')" :value="old('zipcode')" />

                        <hr>

                        <x-admin.forms.select :label="__('Country')" :options="$countries" name="country" :selected="old('country')" />
                        <x-admin.forms.input-text name="state" :label="__('State')" :value="old('state')" />
                        <x-admin.forms.input-text name="city" :label="__('City')" :value="old('city')" />

                        <hr>

                        <div x-data="map">
                            <div class="row g-0">
                                <div class="col-6">
                                    <x-admin.forms.input-text x-model="longitude" name="longitude" :label="__('Longitude')" />
                                </div>
                                <div class="col-6">
                                    <x-admin.forms.input-text x-model="latitude" name="latitude" :label="__('Latitude')" />
                                </div>
                            </div>
    
                            <div>
                                <input type="text" class="form-control w-50 mt-1" id="mapSearch" placeholder="Search">
                                <div id="map" class="w-100" style="height: 300px;"></div>
                            </div>

                            <div>
                                <p class="mt-1 mb-0 text-muted small">Klik pada peta untuk memindahkan penanda. <code>GOOGLE_MAPS_JS_API_KEY</code> di file <code>.env</code> harus diisi agar search berfungsi.</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-0">
                            <div class="col-6">
                                <x-admin.forms.input-text name="check_in" :label="__('Check In')" :value="old('check_in')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="check_out" :label="__('Check Out')" :value="old('check_out')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="number_of_rooms" :label="__('Number of Rooms')" :value="old('number_of_rooms')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="number_of_floors" :label="__('Number of Floors')" :value="old('number_of_floors')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="year_opened" :label="__('Year Opened')" :value="old('year_opened')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="year_renovated" :label="__('Year Renovated')" :value="old('year_renovated')" />
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    @push('scriptsBottom')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('map', () => ({
                    latitude: +{{ old('latitude', 0) }},
                    longitude: +{{ old('longitude', 0) }},
                    init() {
                        this.generateMap();
                    },
                    generateMap() {
                        const initMap = () => {
                            const mapContainer = document.getElementById('map');

                            const center = {
                                lat: this.latitude,
                                lng: this.longitude,
                            };

                            const map = new google.maps.Map(mapContainer, {
                                center: center,
                                zoom: 13,
                                mapId: 'DEMO_MAP_ID',
                            });

                            let marker = new google.maps.Marker({
                                position: center,
                                map: map,
                            });

                            google.maps.event.addListener(marker, 'dragend', (e) => {
                                this.latitude = e.latLng.lat().toFixed(6);
                                this.longitude = e.latLng.lng().toFixed(6);

                                map.panTo(e.latLng);
                            });

                            map.addListener('click', (e) => {
                                marker.setMap(null);

                                marker = new google.maps.Marker({
                                    position: {
                                        lat: e.latLng.lat(),
                                        lng: e.latLng.lng(),
                                    },
                                    map: map,
                                });

                                this.latitude = e.latLng.lat().toFixed(6);
                                this.longitude = e.latLng.lng().toFixed(6);
                            });

                            const input = document.getElementById('mapSearch');
                            const searchBox = new google.maps.places.SearchBox(input);

                            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
                            map.addListener('bounds_changed', () => {
                                searchBox.setBounds(map.getBounds());
                            });

                            searchBox.addListener('places_changed', () => {
                                const places = searchBox.getPlaces();
                                
                                if (places.length == 0) {
                                    return;
                                }

                                marker.setMap(null);

                                const placeLat = places[0].geometry.location.lat();
                                const placeLng = places[0].geometry.location.lng();

                                marker = new google.maps.Marker({
                                    position: {
                                        lat: placeLat,
                                        lng: placeLng,
                                    },
                                    map: map,
                                });

                                map.panTo(places[0].geometry.location);

                                this.latitude = placeLat.toFixed(6);
                                this.longitude = placeLng.toFixed(6);
                            });
                        }
                        window.initMap = initMap;
                    },
                }));
            });
        </script>
        <script async src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places&v=beta&key={{ config('services.google_maps.key_js') }}&rand={{ rand(0, 10000) }}"></script>
    @endpush
</x-admin.layouts.app>