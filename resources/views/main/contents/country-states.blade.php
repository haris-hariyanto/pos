<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_country_states_page_title, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $country['name'],
                '[current_url]' => route('country.states', [$country['slug']]),
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($pagesettings_country_states_meta_data, [
                '[appname]' => $settings__website_name,
                '[country_name]' => $country['name'],
                '[current_url]' => route('country.states', [$country['slug']]),
            ])
        !!}

        <link rel="canonical" href="{{ route('country.states', [$country['slug']]) }}">
    @endpush

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
                        <li class="breadcrumb-item active" aria-current="page">{{ __('States in :country', ['country' => $country['name']]) }}</li>
                    </ol>
                </nav>
            </div>
            <!-- [END] Breadcrumb -->

            <h1 class="fs-2 mb-3">
                {{
                    \App\Helpers\Text::placeholder($pagesettings_country_states_heading, [
                        '[appname]' => $settings__website_name,
                        '[country_name]' => $country['name'],
                        '[current_url]' => route('country.states', [$country['slug']]),
                    ])                    
                }}
            </h1>

        </div>
    </div>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    @foreach ($country['states'] as $state)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="mb-2 tw-line-clamp-1">
                                <a href="{{ route('hotel.location', ['state', $state['slug']]) }}">{{ $state['name'] }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>