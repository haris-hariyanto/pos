<x-main.layouts.app>
    <x-slot:pageTitle>{{ __(':place in :country', ['place' => $category['name'], 'country' => $country['name']]) }}</x-slot:pageTitle>

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">

            <!-- Breadcrumb -->
            <div class="mt-2">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index') }}">{{ __('Home') }}</a>
                        </li>
                        @if (!empty($country['continent']) && !empty($country['continent']['name']))
                            <li class="breadcrumb-item">
                                <a href="{{ route('continent', [$country['continent']['slug']]) }}">{{ $country['continent']['name'] }}</a>
                            </li>
                        @endif
                        <li class="breadcrumb-item">
                            <a href="{{ route('country', [$country['slug']]) }}">{{ $country['name'] }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __(':place in :country', ['place' => $category['name'], 'country' => $country['name']]) }}</li>
                    </ol>
                </nav>
            </div>
            <!-- [END] Breadcrumb -->

            <h1 class="fs-2 mb-3">{{ __(':place in :country', ['place' => $category['name'], 'country' => $country['name']]) }}</h1>

        </div>
    </div>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                @if (count($places) > 0)
                    <div class="row">
                        @foreach ($places as $place)
                            <div class="col-12 col-lg-4">
                                <div class="mb-2">
                                    <div class="tw-line-clamp-1">
                                        <a href="{{ route('place', [$place['place']['slug']]) }}">{{ $place['place']['name'] }}</a>
                                    </div>
                                    <div class="text-muted small tw-line-clamp-1">
                                        {{ $place['place']['address'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div>
                        <p class="mb-0">{{ __('No places available.') }}</p>
                    </div>
                @endif
            </div>
        </div>
        @if (count($places) > 0)
            <div class="my-2">
                {!! $links !!}
            </div>
        @endif
    </div>
</x-main.layouts.app>