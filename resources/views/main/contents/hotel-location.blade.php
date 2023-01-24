<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Hotels in :location, :country', ['location' => $location['name'], 'country' => $location['country']['name']]) }}</x-slot:pageTitle>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($type == 'city' ? $pagesettings_city_page_title : $pagesettings_state_page_title, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $location['country']['name'],
                '[city_name]' => $location['name'],
                '[state_name]' => $location['name'],
                '[page]' => $currentPage,
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($type == 'city' ? $pagesettings_city_meta_data : $pagesettings_state_meta_data, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $location['country']['name'],
                '[city_name]' => $location['name'],
                '[state_name]' => $location['name'],
                '[page]' => $currentPage,
            ])
        !!}
    @endpush

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

                                @if (!empty($location['continent']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('continent', [$location['continent']['slug']]) }}">{{ $location['continent']['name'] }}</a>
                                    </li>
                                @endif

                                @if (!empty($location['country']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('country', [$location['country']['slug']]) }}">{{ $location['country']['name'] }}</a>
                                    </li>
                                @endif

                                <li class="breadcrumb-item active" aria-current="page">{{ $location['name'] }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- [END] Breadcrumb -->

                    <h1 class="fs-2 mb-3">{{ __('Hotels in :location, :country', ['location' => $location['name'], 'country' => $location['country']['name']]) }}</h1>
                </div>
            </div>

        </div>
    </div>

    <div class="p-1">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">
                    @foreach ($hotels as $hotel)
                        <x-main.components.contents.hotel :hotel="$hotel" />
                    @endforeach
                </div>
                <div class="col-12 col-lg-10">
                    {!! $links !!}
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>