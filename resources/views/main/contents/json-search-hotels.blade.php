@foreach ($resultsArray as $result)
    <x-main.components.contents.hotel :hotel="$result" :show-address="true" />
@endforeach
<div class="my-2">
    {{ $results->links('components.main.components.simple-pagination') }}
</div>