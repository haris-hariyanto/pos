<x-main.layouts.app>
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_continent_page_title, [
                '[appname]' => $settings__website_name,
                '[continent_name]' => $continent['name'],
                '[current_url]' => route('continent', [$continent['slug']]),
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($pagesettings_continent_meta_data, [
                '[appname]' => $settings__website_name,
                '[continent_name]' => $continent['name'],
                '[current_url]' => route('continent', [$continent['slug']]),
            ])
        !!}

        <link rel="canonical" href="{{ route('continent', [$continent['slug']]) }}">
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
                        <li class="breadcrumb-item active" aria-current="page">{{ $continent['name'] }}</li>
                    </ol>
                </nav>
            </div>
            <!-- [END] Breadcrumb -->
            
            <h1 class="fs-2 mb-3">
                {{
                    \App\Helpers\Text::placeholder($pagesettings_continent_heading, [
                        '[appname]' => $settings__website_name,
                        '[continent_name]' => $continent['name'],
                        '[current_url]' => route('continent', [$continent['slug']]),
                    ])
                }}
            </h1>
        </div>
    </div>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row">
                    @foreach ($continent['countries'] as $country)
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img src="https://flagsapi.com/{{ $country['iso_code'] }}/flat/24.png" alt="" loading="lazy" width="24" height="24">
                                    </div>
                                    <div class="tw-line-clamp-1">
                                        <a href="{{ route('country', [$country['slug']]) }}">{{ $country['name'] }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>