<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit Hotel') }}</x-slot>
    <div class="row">
        <div class="col-12">
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

            <div class="card">
                <div class="card-body">
                    <p class="mb-0">{{ __('URL') }} : <a href="{{ route('hotel', [$hotel['slug']]) }}" target="_blank">{{ route('hotel', [$hotel['slug']]) }}</a></p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">

            <form action="{{ route('admin.hotels.update', ['hotel' => $hotel]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header font-weight-bold">{{ __('Hotel Data') }}</div>
                    <div class="card-body">
    
                        <x-admin.forms.input-text name="name" :label="__('Name')" :value="old('name', $hotel)" />
    
                        <div class="row g-0">
                            <div class="col-6">
                                <x-admin.forms.input-text name="formerly_name" :label="__('Formerly Name')" :value="old('formerly_name', $hotel->formerly_name)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="translated_name" :label="__('Translated Name')" :value="old('translated_name', $hotel->translated_name)" />
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
                                            <x-admin.forms.radio :label="$star" name="star_rating" :value="$star" :selected="$star == $hotel->star_rating" />
                                        </div>
                                    @endforeach
                                    <div class="col-3">
                                        <x-admin.forms.radio :label="__('Unrated')" name="star_rating" value="unrated" :selected="empty($hotel->star_rating)" />
                                    </div>
                                </div>
                                @error('star_rating')
                                    <div class="small text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
    
                        <hr>
    
                        <x-admin.forms.input-text name="url" :label="__('URL')" :value="old('url', $hotel->url)" />
                        
                        <div class="row g-0">
                            <div class="col-6">
                                <x-admin.forms.input-text name="price" :label="__('Rates From')" :value="old('price', $hotel->price)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="rates_currency" :label="__('Rates Currency')" :value="old('rates_currency', $hotel->rates_currency)" />
                            </div>
                        </div>
    
                        <x-admin.forms.textarea name="overview" :label="__('Overview')">{{ old('overview', $hotel->overview) }}</x-admin.forms.textarea>
    
                        <hr>
    
                        <div class="row g-0">
                            <div class="col-6">
                                <x-admin.forms.input-text name="brand" :label="__('Hotel Brand')" :value="old('brand', $hotel->brand)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="chain" :label="__('Hotel Chain')" :value="old('chain', $hotel->chain)" />
                            </div>
                        </div>
    
                        <hr>
                        
                        <x-admin.forms.textarea name="address_line_1" :label="__('Address Line 1')" rows="2">{{ old('address_line_1', $hotel->address_line_1) }}</x-admin.forms.textarea>
                        <x-admin.forms.textarea name="address_line_2" :label="__('Address Line 2')" rows="2">{{ old('address_line_2', $hotel->address_line_2) }}</x-admin.forms.textarea>
                        <x-admin.forms.input-text name="zipcode" :label="__('ZIP Code')" :value="old('zipcode', $hotel->zipcode)" />
    
                        <hr>

                        <x-admin.forms.select :label="__('Country')" :options="$countries" name="country" :selected="$hotel->country" />
                        <x-admin.forms.input-text name="state" :label="__('State')" :value="old('state', $hotel->state)" />
                        <x-admin.forms.input-text name="city" :label="__('City')" :value="old('city', $hotel->city)" />

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
                                <x-admin.forms.input-text name="check_in" :label="__('Check In')" :value="old('check_in', $hotel->check_in)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="check_out" :label="__('Check Out')" :value="old('check_out', $hotel->check_out)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="number_of_rooms" :label="__('Number of Rooms')" :value="old('number_of_rooms', $hotel->number_of_rooms)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="number_of_floors" :label="__('Number of Floors')" :value="old('number_of_floors', $hotel->number_of_floors)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="year_opened" :label="__('Year Opened')" :value="old('year_opened', $hotel->year_opened)" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="year_renovated" :label="__('Year Renovated')" :value="old('year_renovated', $hotel->year_renovated)" />
                            </div>
                        </div>
    
                        <x-admin.forms.input-text name="accommodation_type" :label="__('Accommodation Type')" :value="old('accommodation_type', $hotel->accommodation_type)" />
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save Hotel Data') }}</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-12 col-lg-6">

            <form action="{{ route('admin.hotels.update-cover', [$hotel['slug']]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card" x-data="photos">
                    <div class="card-header font-weight-bold">{{ __('Photos') }}</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <x-admin.forms.radio :label="__('Hotlink')" name="photosType" value="hotlink" x-model="currentMode" :selected="true" @change="changeMode('hotlink')" />
                            <x-admin.forms.radio :label="__('Upload')" name="photosType" value="upload" x-model="currentMode" @change="changeMode('upload')" />
                        </div>
    
                        <div x-show="currentMode == 'hotlink'">
                            @for ($i = 0; $i < 5; $i++)
                                <x-admin.forms.input-text-array :label="__('Photo') . ' ' . ($i + 1)" name="photos_hotlinks" index="{{ $i }}" :value="old('photos_hotlinks.' . $i, !empty($hotel->photos[$i]) ? $hotel->photos[$i] : '')" />
                            @endfor
                        </div>
    
                        <div x-show="currentMode == 'upload'">
                            @for ($i = 0; $i < 5; $i++)
                                <x-admin.forms.file-array :label="__('Photo') . ' ' . ($i + 1)" name="photos_uploads" index="{{ $i }}" value="" />
                            @endfor
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save Photos') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('photos', () => ({
                    currentMode: {!! session('tab') == 'upload' || old('photosType') == 'upload' ? "'upload'" : "'hotlink'" !!},
                    changeMode(mode) {
                        this.currentMode = mode;
                    },
                }));

                Alpine.data('map', () => ({
                    latitude: +'{{ old('latitude', $hotel->latitude) }}',
                    longitude: +'{{ old('longitude', $hotel->longitude) }}',
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

            $(function () {
                bsCustomFileInput.init();
            });
        </script>
        <script async src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places&v=beta&key={{ config('services.google_maps.key_js') }}&rand={{ rand(0, 10000) }}"></script>
    @endpush
</x-admin.layouts.app>