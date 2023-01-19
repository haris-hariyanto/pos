<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Best Hotels Near :place', ['place' => $place['name']]) }}</x-slot:pageTitle>

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    <!-- Breadcrumb -->
                    <div class="mt-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('index') }}">{{ __('Home') }}</a>
                                </li>

                                @if (!empty($place['continent']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('continent', [Str::slug($place['continent']['slug'])]) }}">{{ $place['continent']['name'] }}</a>
                                    </li>
                                @endif

                                @if (!empty($place['country']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('country', [Str::slug($place['country']['slug'])]) }}">{{ $place['country']['name'] }}</a>
                                    </li>
                                @endif

                                <li class="breadcrumb-item active" aria-current="page">{{ $place['name'] }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- [END] Breadcrumb -->

                    <h1 class="fs-2 mb-3">{{ __('Best Hotels Near :place', ['place' => $place['name']]) }}</h1>
                    <p class="lead">{{ $place['address'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-1">
        <div class="container pb-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                </div>
            </div>
        </div>
    
        <div class="container pb-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    @foreach ($hotels as $hotel)
                        <x-main.components.contents.hotel :hotel="$hotel['hotel']" :place-and-distance="['place' => $place['name'], 'distance' => $hotel['m_distance']]" />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>