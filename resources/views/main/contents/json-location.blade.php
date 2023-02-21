@forelse ($hotels as $hotel)
    <x-main.components.contents.hotel :hotel="$hotel" :is-from-json="true" />
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