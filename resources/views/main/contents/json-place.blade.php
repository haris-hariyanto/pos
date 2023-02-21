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
        <x-main.components.contents.hotel :hotel="$hotel['hotel']" :place-and-distance="['place' => $place['name'], 'distance' => $hotel['m_distance']]" :is-from-json="true" />
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