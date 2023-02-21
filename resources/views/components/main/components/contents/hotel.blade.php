@props(['hotel', 'placeAndDistance' => false, 'showAddress' => false, 'isFromJson' => false])

<div class="card shadow-sm mb-2">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-12 col-lg-4">
                <div class="me-0 me-lg-1 mb-3 mb-lg-0 d-flex justify-content-center align-items-center h-100">
                    @if (!empty($hotel['photos'][0]))
                        @if (config('content.lazy_load') && $isFromJson == false)
                            <img data-src="{{ \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) }}" alt="{{ $hotel['name'] }}" class="img-fluid rounded tw-max-h-72 lazy">
                        @else
                            <img src="{{ \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) }}" alt="{{ $hotel['name'] }}" class="img-fluid rounded tw-max-h-72" loading="lazy">
                        @endif
                    @else
                        <img src="{{ asset('assets/main/images/no-image.png') }}" alt="No image" class="img-fluid rounded tw-max-h-72" loading="lazy">
                    @endif
                </div>
            </div>
            <div class="col-12 col-lg-8 d-flex flex-column">
                <div class="flex-grow-1">
                    @if (!empty($hotel['star_rating']))
                        <div class="mb-1">
                            @for ($i = 0; $i < floor($hotel['star_rating']); $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                </svg>
                            @endfor
                            @if (str_contains($hotel['star_rating'], '.'))
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-half tw-text-orange-400" viewBox="0 0 16 16">
                                    <path d="M5.354 5.119 7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.548.548 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.52.52 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.58.58 0 0 1 .085-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.565.565 0 0 1 .162-.505l2.907-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.001 2.223 8 2.226v9.8z"/>
                                </svg>
                            @endif
                        </div>
                    @endif
                    <h2 class="tw-text-xl tw-font-semibold mb-1">
                        <a href="{{ route('hotel', [$hotel['slug']]) }}">{{ $hotel['name'] }}</a>
                    </h2>

                    @if (!empty($placeAndDistance))
                        <div class="small text-muted mb-1">{{ __(':distance from :place', ['distance' => number_format($placeAndDistance['distance'] / 1000, 1) . ' KM', 'place' => $placeAndDistance['place']]) }}</div>
                    @endif

                    @if ($showAddress && !empty($hotel['city']) && !empty($hotel['country']))
                        <div class="fw-bold my-2 d-flex align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
                            </svg>
                            <div class="ms-1">{{ $hotel['city'] }}, {{ $hotel['country'] }}</div>
                        </div>
                    @endif

                    <div>
                        @if (!empty(trim($hotel['overview'])))
                            <p class="tw-line-clamp-3 mb-2">{{ $hotel['overview'] }}</p>
                        @endif

                        @if (!empty($hotel['price']) && !empty($hotel['rates_currency']))
                            <p class="tw-line-clamp-1 mb-3">{{ __('Rates from') }} : <b>{{ \App\Helpers\Text::price($hotel['price'], $hotel['rates_currency']) }}</b></p>
                        @endif
                    </div>
                </div>
                <div>
                    <a href="{{ route('hotel', [$hotel['slug']]) }}" class="btn btn-secondary">{{ __('Detail') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>