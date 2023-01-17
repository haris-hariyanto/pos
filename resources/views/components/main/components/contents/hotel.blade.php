@props(['hotel'])

<div class="card shadow-sm mb-2">
    <div class="card-body">
        <div class="row g-2">
            <div class="col-12 col-lg-4">
                <div class="me-0 me-lg-1 mb-3 mb-lg-0 d-flex justify-content-center align-items-center h-100">
                    <img src="{{ \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) }}" alt="{{ $hotel['name'] }}" class="img-fluid rounded tw-max-h-72" loading="lazy">
                </div>
            </div>
            <div class="col-12 col-lg-8 d-flex flex-column">
                <div class="flex-grow-1">
                    <div class="mb-1">
                        @for ($i = 0; $i < $hotel['star_rating']; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        @endfor
                    </div>
                    <h2 class="tw-text-xl tw-font-semibold mb-1">
                        <a href="{{ route('hotel', [$hotel['slug']]) }}">{{ $hotel['name'] }}</a>
                    </h2>
                    <div>
                        <p class="tw-line-clamp-3 mb-2">{{ $hotel['overview'] }}</p>
                        <p class="tw-line-clamp-1 mb-3">{{ __('Rates from') }} : <b>{{ !empty($hotel['rates_from']) ? $hotel['rates_from'] : $hotel['rates_from_exclusive'] }} {{ $hotel['rates_currency'] }}</b></p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('hotel', [$hotel['slug']]) }}" class="btn btn-secondary">{{ __('Detail') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>