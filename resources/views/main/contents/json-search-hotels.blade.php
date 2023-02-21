@forelse ($resultsArray as $result)
    <x-main.components.contents.hotel :hotel="$result" :show-address="true" :is-from-json="true" />
@empty
    <div class="card shadow-sm">
        <div class="card-body">
            <p class="mb-0">{{ __('No hotels available.') }}</p>
        </div>
    </div>
@endforelse
<div class="my-2">
    {{ $results->links('components.main.components.simple-pagination') }}
</div>