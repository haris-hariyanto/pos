<x-main.layouts.app>
    <x-slot:pageTitle>{{ $hotel['name'] }}</x-slot:pageTitle>

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
                                @if (!empty($hotel['continent']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('continent', [$hotel['continent']['slug']]) }}">{{ $hotel['continent']['name'] }}</a>
                                    </li>
                                @endif
                                @if (!empty($hotel['country']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('country', [$hotel['country']['slug']]) }}">{{ $hotel['country']['name'] }}</a>
                                    </li>
                                @endif
                                @if (!empty($hotel['state']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('hotel.location', ['state', $hotel['state']['slug']]) }}">{{ $hotel['state']['name'] }}</a>
                                    </li>
                                @endif
                                @if (!empty($hotel['city']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('hotel.location', ['city', $hotel['city']['slug']]) }}">{{ $hotel['city']['name'] }}</a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active tw-line-clamp-1" aria-current="page">{{ $hotel['name'] }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- [END] Breadcrumb -->

                    <h1 class="fs-2 mb-2">{{ $hotel['name'] }}</h1>
                    <div class="mb-3">
                        @for ($i = 0; $i < $hotel['star_rating']; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="p-1">
        <div class="container pt-4 mb-2">
            <div class="row justify-content-center">
                <div class="col-12">
    
                    <div class="card shadow-sm">
                        <div class="card-body">
    
                            <div class="row g-2">
                                <div class="col-12 col-lg-6">
                                    <div class="row g-2 my-0 h-100">
                                        <div class="col-12 d-flex">
                                            <img src="{{ \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) }}" class="rounded img-cover" loading="lazy" alt="{{ $hotel['name'] }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="row g-2 my-0 h-100">
                                        @foreach ($hotel['photos'] as $index => $photo)
                                            @if ($index > 0)
                                                <div class="col-6 d-flex">
                                                    <img src="{{ \App\Helpers\Image::removeQueryParameters($photo) }}" class="rounded img-cover-small" loading="lazy" alt="{{ $hotel['name'] }}">
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
    
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    
        <div class="container pb-3">
            <div class="row justify-content-center">
                <div class="col-12">
    
                    <!-- Content -->
                    <div class="row g-2">
                        <div class="col-12 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    @if (!empty($hotel['overview']))
                                        <p>{{ $hotel['overview'] }}</p>
                                    @endif

                                    <!-- Table -->
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover align-middle">
                                            <tr>
                                                <td class="w-25">{{ __('Hotel Name') }}</td>
                                                <td class="fw-bold">
                                                    <div>{{ $hotel['name'] }}</div>
                                                    
                                                    @if (!empty($hotel['formerly_name']))
                                                        <div class="mt-2">{{ $hotel['formerly_name'] }} ({{ __('Formerly Name') }})</div>
                                                    @endif

                                                    @if (!empty($hotel['translated_name']) && $hotel['translated_name'] != $hotel['name'])
                                                        <div class="mt-2">{{ $hotel['translated_name'] }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if (!empty($hotel['star_rating']) > 0)
                                                <tr>
                                                    <td class="w-25">{{ __('Star Rating') }}</td>
                                                    <td class="pt-1 pb-2">
                                                        @for ($i = 0; $i < $hotel['star_rating']; $i++)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                            </svg>
                                                        @endfor
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['chain']))
                                                <tr>
                                                    <td>{{ __('Hotel Chain') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="#">{{ $hotel['chain'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['brand']))
                                                <tr>
                                                    <td>{{ __('Brand') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="#">{{ $hotel['brand'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['address_line_1']))
                                                <tr>
                                                    <td>{{ __('Address') }}</td>
                                                    <td class="fw-bold">{{ $hotel['address_line_1'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['city']) && is_array($hotel['city']))
                                                <tr>
                                                    <td>{{ __('City') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="{{ route('hotel.location', ['city', $hotel['city']['slug']]) }}">{{ $hotel['city']['name'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['state']) && is_array($hotel['state']))
                                                <tr>
                                                    <td>{{ __('State / Province') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="{{ route('hotel.location', ['state', $hotel['state']['slug']]) }}">{{ $hotel['state']['name'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['country']))
                                                <tr>
                                                    <td>{{ __('Country') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="{{ route('country', $hotel['country']['slug']) }}">{{ $hotel['country']['name'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['year_opened']))
                                                <tr>
                                                    <td>{{ __('Year Opened') }}</td>
                                                    <td class="fw-bold">{{ $hotel['year_opened'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['year_renovated']))
                                                <tr>
                                                    <td>{{ __('Year Renovated') }}</td>
                                                    <td class="fw-bold">{{ $hotel['year_renovated'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['number_of_rooms']))
                                                <tr>
                                                    <td>{{ __('Number of Rooms') }}</td>
                                                    <td class="fw-bold">{{ number_format($hotel['number_of_rooms'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['number_of_floors']))
                                                <tr>
                                                    <td>{{ __('Number of Floors') }}</td>
                                                    <td class="fw-bold">{{ $hotel['number_of_floors'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['check_in']))
                                                <tr>
                                                    <td>{{ __('Check In') }}</td>
                                                    <td class="fw-bold">{{ $hotel['check_in'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['check_out']))
                                                <tr>
                                                    <td>{{ __('Check Out') }}</td>
                                                    <td class="fw-bold">{{ $hotel['check_out'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['rates_from']) && !empty($hotel['rates_currency']))
                                                <tr>
                                                    <td>{{ __('Rates from') }}</td>
                                                    @if ($hotel['rates_currency'] == 'IDR')
                                                        <td class="fw-bold">{{ $hotel['rates_currency'] }} {{ number_format($hotel['rates_from'], 0, ',', '.') }}</td>
                                                    @else
                                                        <td class="fw-bold">{{ number_format($hotel['rates_from'], 0, '.', ',') }} {{ $hotel['rates_currency'] }}</td>
                                                    @endif
                                                </tr>
                                            @endif
                                            @if (empty($hotel['rates_from']) && !empty($hotel['rates_from_exclusive']) && !empty($hotel['rates_currency']))
                                                <tr>
                                                    <td>{{ __('Rates from') }}</td>
                                                    @if ($hotel['rates_currency'] == 'IDR')
                                                        <td class="fw-bold">{{ $hotel['rates_currency'] }} {{ number_format($hotel['rates_from_exclusive'], 0, ',', '.') }}</td>
                                                    @else
                                                        <td class="fw-bold">{{ number_format($hotel['rates_from_exclusive'], 0, '.', ',') }} {{ $hotel['rates_currency'] }}</td>
                                                    @endif
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <!-- [END] Table -->

                                    <div class="d-grid d-lg-block mb-2">
                                        <!-- Booking link -->
                                        <a href="{{ $hotel['url'] }}" class="btn btn-secondary px-5">{{ __('Book via :platform', ['platform' => 'Agoda']) }}</a>
                                        <!-- [END] Booking link -->
                                    </div>

                                    <hr>

                                    @if (count($paragraphs) > 0)
                                        <h2 class="fs-5">{{ __('About :hotel', ['hotel' => $hotel['name']]) }}</h2>
                                        @foreach ($paragraphs as $paragraph)
                                            <p>{{ $paragraph }}</p>
                                        @endforeach
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <!-- Map -->
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h2 class="fs-5">{{ __(':hotel Location', ['hotel' => $hotel['name']]) }}</h2>
                                    <div>
                                        @if (!empty($hotel['address_line_1']))
                                            <div>{{ $hotel['address_line_1'] }}</div>
                                        @endif
                                        @if (!empty($hotel['address_line_2']))
                                            <div>{{ $hotel['address_line_2'] }}</div>
                                        @endif
                                        @if (!empty($hotel['zipcode']))
                                            <div>{{ $hotel['zipcode'] }}</div>
                                        @endif
                                    </div>
                                    @if (!empty($hotel['longitude']) && !empty($hotel['latitude']))
                                        <div class="mt-3">
                                            <iframe 
                                                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&center={{ $hotel['latitude'] . ',' . $hotel['longitude'] }}&q={{ urlencode($hotel['name']) }}"
                                                class="border-0 w-100 tw-h-96"
                                                frameborder="0"
                                                referrerpolicy="no-referrer-when-downgrade"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- [END] Map -->
                        </div>
                    </div>
                    <!-- [END] Content -->
    
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>