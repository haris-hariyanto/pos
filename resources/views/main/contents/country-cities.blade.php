<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Cities in :country', ['country' => $country['name']]) }}</x-slot:pageTitle>

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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Cities in :country', ['country' => $country['name']]) }}</li>
                    </ol>
                </nav>
            </div>
            <!-- [END] Breadcrumb -->

            <h1 class="fs-2 mb-3">{{ __('Cities in :country', ['country' => $country['name']]) }}</h1>

        </div>
    </div>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    @foreach ($country['cities'] as $city)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="mb-2 tw-line-clamp-1">
                                <a href="{{ route('hotel.location', ['city', $city['slug']]) }}">{{ $city['name'] }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>