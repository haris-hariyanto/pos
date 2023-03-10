<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Search Result : :q', ['q' => $query]) }}</x-slot:pageTitle>

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">
            <h1 class="fs-2 mb-3 mt-2">{{ __('Search Result : :q', ['q' => $query]) }}</h1>
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-2">
            @forelse ($results as $result)
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-1">
                                @if ($isSearchFromAPI)
                                    <a href="{{ $result['url'] }}" class="fw-bold tw-line-clamp-1">{{ $result['name'] }}</a>
                                @else
                                    <a href="{{ route('place', [$result['slug']]) }}" class="fw-bold tw-line-clamp-1">{{ $result['name'] }}</a>
                                @endif
                            </div>
                            <div class="small text-muted tw-line-clamp-1">{{ $result['address'] }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <p class="mb-0">{{ __('No place available.') }}</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
        @if (!$isSearchFromAPI)
            <div class="my-2">
                {{ $results->links('components.main.components.simple-pagination') }}
            </div>
        @endif
    </div>
</x-main.layouts.app>