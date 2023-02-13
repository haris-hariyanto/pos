<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @stack('metaData')

        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

        <title>{!! $pageTitle . ' - ' . $settings__website_name ?? $settings__website_name !!}</title>

        @vite(['resources/js/app.js'])
        @vite(['resources/css/tailwind.css'])

        @stack('scripts')

        {!! $settings__header_script !!}
    </head>
    <body>
        @include('main.layouts.navbar')

        {{ $slot }}

        @include('main.layouts.footer')

        @stack('scriptsBottom')
        <script>
            const images = document.querySelectorAll('img');
            images.forEach(image => {
                image.addEventListener('error', () => {
                    image.src = '{{ asset('assets/main/images/no-image.png') }}';
                });
            });

            document.addEventListener('alpine:init', () => {
                Alpine.data('searchForm', () => ({
                    mode: 'findHotels',
                    changeMode(mode) {
                        if (mode == 'findHotels' || mode == 'findPlaces') {
                            this.mode = mode;
                        }
                    }
                }));
            });
        </script>

        {!! $settings__footer_script !!}

        @if($useRecaptcha)
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        @endif
    </body>
</html>