<x-main.layouts.app  :use-recaptcha="true">
    <x-slot:pageTitle>
        {{ 
            \App\Helpers\Text::placeholder($pagesettings_hotel_page_title, [
                '[appname]' => $settings__website_name,
                '[hotel_name]' => $hotel['name'],
                '[current_url]' => route('hotel', [$hotel['slug']]),
                '[hotel_address]' => $hotel['address_line_1'],
                '[hotel_city]' => isset($hotel['city']['name']) ? $hotel['city']['name'] : '-',
                '[hotel_state]' => isset($hotel['state']['name']) ? $hotel['state']['name'] : '-',
                '[hotel_country]' => isset($hotel['country']['name']) ? $hotel['country']['name'] : '-',
                '[hotel_image]' => !empty($hotel['photos']) && !empty($hotel['photos'][0]) ? \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) : false,
            ])
        }}
    </x-slot:pageTitle>

    @push('metaData')
        {!!
            \App\Helpers\Text::placeholder($pagesettings_hotel_meta_data, [
                '[appname]' => $settings__website_name,
                '[hotel_name]' => $hotel['name'],
                '[current_url]' => route('hotel', [$hotel['slug']]),
                '[hotel_address]' => $hotel['address_line_1'],
                '[hotel_city]' => isset($hotel['city']['name']) ? $hotel['city']['name'] : '-',
                '[hotel_state]' => isset($hotel['state']['name']) ? $hotel['state']['name'] : '-',
                '[hotel_country]' => isset($hotel['country']['name']) ? $hotel['country']['name'] : '-',
                '[hotel_image]' => !empty($hotel['photos']) && !empty($hotel['photos'][0]) ? \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) : false,
            ])
        !!}

        <link rel="canonical" href="{{ route('hotel', $hotel['slug']) }}">

        {!! $structuredData->render() !!}
    @endpush

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">

            <div class="row justify-content-center">
                <div class="col-12">
                    <!-- Breadcrumb -->
                    <div class="mt-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('index') }}">{{ __('Home') }}</a>
                                </li>
                                @if (!empty($hotel['continent']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('continent', [$hotel['continent']['slug']]) }}">{{ $hotel['continent']['name'] }}</a>
                                    </li>
                                @endif
                                @if (!empty($hotel['country']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('country', [$hotel['country']['slug']]) }}">{{ $hotel['country']['name'] }}</a>
                                    </li>
                                @endif
                                @if (!empty($hotel['state']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('hotel.location', [config('content.location_term_state'), $hotel['state']['slug']]) }}">{{ $hotel['state']['name'] }}</a>
                                    </li>
                                @endif
                                @if (!empty($hotel['city']))
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('hotel.location', [config('content.location_term_city'), $hotel['city']['slug']]) }}">{{ $hotel['city']['name'] }}</a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active tw-line-clamp-1" aria-current="page">{{ $hotel['name'] }}</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- [END] Breadcrumb -->

                    <h1 class="fs-2 mb-2">
                        {{ 
                            \App\Helpers\Text::placeholder($pagesettings_hotel_heading, [
                                '[appname]' => $settings__website_name,
                                '[hotel_name]' => $hotel['name'],
                                '[current_url]' => route('hotel', [$hotel['slug']]),
                                '[hotel_address]' => $hotel['address_line_1'],
                                '[hotel_city]' => isset($hotel['city']['name']) ? $hotel['city']['name'] : '-',
                                '[hotel_state]' => isset($hotel['state']['name']) ? $hotel['state']['name'] : '-',
                                '[hotel_country]' => isset($hotel['country']['name']) ? $hotel['country']['name'] : '-',
                                '[hotel_image]' => !empty($hotel['photos']) && !empty($hotel['photos'][0]) ? \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) : false,
                            ])
                        }}
                    </h1>
                    <div class="mb-3">
                        @for ($i = 0; $i < floor($hotel['star_rating']); $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        @endfor
                        @if (str_contains($hotel['star_rating'], '.'))
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star-half tw-text-orange-400" viewBox="0 0 16 16">
                                <path d="M5.354 5.119 7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.548.548 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.52.52 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.58.58 0 0 1 .085-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.565.565 0 0 1 .162-.505l2.907-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.001 2.223 8 2.226v9.8z"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="p-1">

        <!-- Images -->
        <div class="container pt-4 mb-2">
            <div class="row justify-content-center">
                <div class="col-12">
    
                    <div class="card shadow-sm">
                        <div class="card-body">
    
                            <div class="row g-2">
                                <div class="col-12 col-lg-6">
                                    <div class="row g-2 my-0 h-100">
                                        <div class="col-12 d-flex">
                                            @if (!empty($hotel['photos'][0]))
                                                <img src="{{ \App\Helpers\Image::removeQueryParameters($hotel['photos'][0]) }}" class="rounded img-cover" loading="lazy" alt="{{ $hotel['name'] }}">
                                            @else
                                                <img src="{{ asset('assets/main/images/no-image.png') }}" class="rounded img-cover" loading="lazy" alt="No image">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="row g-2 my-0 h-100">
                                        @foreach ($hotel['photos'] as $index => $photo)
                                            @if ($index > 0)
                                                @if (!empty($photo))
                                                    <div class="col-6 d-flex">
                                                        <img src="{{ \App\Helpers\Image::removeQueryParameters($photo) }}" class="rounded img-cover-small" loading="lazy" alt="{{ $hotel['name'] }}">
                                                    </div>
                                                @else
                                                    <div class="col-6 d-flex">
                                                        <img src="{{ asset('assets/main/images/no-image.png') }}" class="rounded img-cover-small" loading="lazy" alt="No image">
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
    
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
        <!-- [END] Images -->
    
        <div class="container pb-3">
            <div class="row justify-content-center">
                <div class="col-12">
    
                    <!-- Content -->
                    <div class="row g-2">
                        <div class="col-12 col-lg-8">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    @if (!empty($hotel['overview']))
                                        <p>{{ $hotel['overview'] }}</p>
                                    @endif

                                    <!-- Table -->
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover align-middle">
                                            <tr>
                                                <td class="w-25">{{ __('Hotel Name') }}</td>
                                                <td class="fw-bold">
                                                    <div>{{ $hotel['name'] }}</div>
                                                    
                                                    @if (!empty($hotel['formerly_name']))
                                                        <div class="mt-2">{{ $hotel['formerly_name'] }} ({{ __('Formerly Name') }})</div>
                                                    @endif

                                                    @if (!empty($hotel['translated_name']) && $hotel['translated_name'] != $hotel['name'])
                                                        <div class="mt-2">{{ $hotel['translated_name'] }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if (!empty($hotel['star_rating']) > 0)
                                                <tr>
                                                    <td class="w-25">{{ __('Star Rating') }}</td>
                                                    <td class="pt-1 pb-2">
                                                        @for ($i = 0; $i < floor($hotel['star_rating']); $i++)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                            </svg>
                                                        @endfor
                                                        @if (str_contains($hotel['star_rating'], '.'))
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-half tw-text-orange-400" viewBox="0 0 16 16">
                                                                <path d="M5.354 5.119 7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.548.548 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.52.52 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.58.58 0 0 1 .085-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.565.565 0 0 1 .162-.505l2.907-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.001 2.223 8 2.226v9.8z"/>
                                                            </svg>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['chain']))
                                                <tr>
                                                    <td>{{ __('Hotel Chain') }}</td>
                                                    <td class="fw-bold">{{ $hotel['chain'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['brand']))
                                                <tr>
                                                    <td>{{ __('Brand') }}</td>
                                                    <td class="fw-bold">{{ $hotel['brand'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['address_line_1']))
                                                <tr>
                                                    <td>{{ __('Address') }}</td>
                                                    <td class="fw-bold">{{ $hotel['address_line_1'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['city']) && is_array($hotel['city']))
                                                <tr>
                                                    <td>{{ __('City') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="{{ route('hotel.location', [config('content.location_term_city'), $hotel['city']['slug']]) }}">{{ $hotel['city']['name'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['state']) && is_array($hotel['state']))
                                                <tr>
                                                    <td>{{ __('State / Province') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="{{ route('hotel.location', [config('content.location_term_state'), $hotel['state']['slug']]) }}">{{ $hotel['state']['name'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['country']))
                                                <tr>
                                                    <td>{{ __('Country') }}</td>
                                                    <td class="fw-bold">
                                                        <a href="{{ route('country', $hotel['country']['slug']) }}">{{ $hotel['country']['name'] }}</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['year_opened']))
                                                <tr>
                                                    <td>{{ __('Year Opened') }}</td>
                                                    <td class="fw-bold">{{ $hotel['year_opened'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['year_renovated']))
                                                <tr>
                                                    <td>{{ __('Year Renovated') }}</td>
                                                    <td class="fw-bold">{{ $hotel['year_renovated'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['number_of_rooms']))
                                                <tr>
                                                    <td>{{ __('Number of Rooms') }}</td>
                                                    <td class="fw-bold">{{ number_format($hotel['number_of_rooms'], 0, ',', '.') }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['number_of_floors']))
                                                <tr>
                                                    <td>{{ __('Number of Floors') }}</td>
                                                    <td class="fw-bold">{{ $hotel['number_of_floors'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['check_in']))
                                                <tr>
                                                    <td>{{ __('Check In') }}</td>
                                                    <td class="fw-bold">{{ $hotel['check_in'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['check_out']))
                                                <tr>
                                                    <td>{{ __('Check Out') }}</td>
                                                    <td class="fw-bold">{{ $hotel['check_out'] }}</td>
                                                </tr>
                                            @endif
                                            @if (!empty($hotel['price']) && !empty($hotel['rates_currency']))
                                                <tr>
                                                    <td>{{ __('Rates from') }}</td>
                                                    <td class="fw-bold">{{ \App\Helpers\Text::price($hotel['price'], $hotel['rates_currency']) }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <!-- [END] Table -->

                                    <div class="d-grid d-lg-block mb-2">
                                        <!-- Booking link -->
                                        <a href="{{ $hotel['url'] . $settings__agoda_suffix }}" class="btn btn-secondary px-5">{{ __('Book via :platform', ['platform' => 'Agoda']) }}</a>
                                        <!-- [END] Booking link -->
                                    </div>

                                    <hr>

                                    @if (count($paragraphs) > 0)
                                        <h2 class="fs-5">{{ __('About :hotel', ['hotel' => $hotel['name']]) }}</h2>
                                        @foreach ($paragraphs as $paragraph)
                                            <p>{{ $paragraph }}</p>
                                        @endforeach
                                    @endif

                                    @if (count($nearbyPlaces) > 0)
                                        <h2 class="fs-5">{{ __('Nearby Places') }}</h2>
                                        <ul>
                                            @foreach ($nearbyPlaces as $nearbyPlace)
                                                <li>
                                                    <div><a href="{{ route('place', [$nearbyPlace['place']['slug']]) }}">{{ $nearbyPlace['place']['name'] }}</a> ({{ __('about :distance', ['distance' => number_format($nearbyPlace['m_distance'] / 1000, 1) . ' KM']) }})</div>
                                                    <div class="text-muted small">{{ $nearbyPlace['place']['address'] }}</div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </div>
                            </div>

                        </div>
                        <div class="col-12 col-lg-4">
                            <!-- Map -->
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h2 class="fs-5">{{ __(':hotel Location', ['hotel' => $hotel['name']]) }}</h2>
                                    <div>
                                        @if (!empty($hotel['address_line_1']))
                                            <div>{{ $hotel['address_line_1'] }}</div>
                                        @endif
                                        @if (!empty($hotel['address_line_2']))
                                            <div>{{ $hotel['address_line_2'] }}</div>
                                        @endif
                                        @if (!empty($hotel['zipcode']))
                                            <div>{{ $hotel['zipcode'] }}</div>
                                        @endif
                                    </div>
                                    @if (!empty($hotel['longitude']) && !empty($hotel['latitude']))
                                        <div class="mt-3">
                                            <iframe 
                                                src="https://www.google.com/maps/embed/v1/place?key={{ config('services.google_maps.key') }}&center={{ $hotel['latitude'] . ',' . $hotel['longitude'] }}&q={{ urlencode($hotel['name']) }}"
                                                class="border-0 w-100 tw-h-96"
                                                frameborder="0"
                                                referrerpolicy="no-referrer-when-downgrade"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <!-- [END] Map -->
                        </div>
                    </div>
                    <!-- [END] Content -->

                    <!-- Reviews -->
                    <div class="row g-2">
                        <div class="col-12 col-lg-8">
                            <h2 class="fs-5 my-3">{{ __('Reviews for :hotel', ['hotel' => $hotel['name']]) }}</h2>

                            <div id="commentsAndReplies" x-data="comments">
                                {!! $reviewsAndReplies !!}

                                <!-- Reply form -->
                                <div class="d-none">
                                    <div id="replyForm" x-ref="replyForm">
                                        <div class="card shadow-sm mb-2">
                                            <div class="card-body">
                                                <x-main.forms.textarea name="reply" :label="__('Reply')" rows="2"></x-main.forms.textarea>
                                                <x-main.forms.input-text name="name" :label="__('Your name')" />
                                                <div class="mb-3">
                                                    {{-- <div class="g-recaptcha" data-sitekey="{{ config('services.grecaptcha.site_key') }}" data-size="invisible"></div> --}}
                                                    <div id="captchaContainer"></div>
                                                </div>
                                                <button class="btn btn-primary" type="submit">{{ __('Send Reply') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- [END] Reply form -->
                            </div>

                            <!-- Comment form -->
                            <form action="{{ route('reviews.store', ['hotel' => $hotel['id']]) }}#commentBox" method="POST">
                                @csrf

                                <div class="card shadow-sm mb-2" id="commentBox">
                                    <div class="card-body">
                                        <x-main.forms.textarea name="review" :label="__('Review')" rows="5">{{ old('review') }}</x-main.forms.textarea>
                                        <div class="row">
                                            <div class="col-6">
                                                <x-main.forms.input-text name="name" :label="__('Your name')" :value="old('name')" />
                                            </div>
                                            <div class="col-6">
                                                <div class="mb-1">{{ __('Rating') }}</div>
                                                <div x-data="rating">
                                                    <input type="hidden" name="rating" x-model="currentStarRating">
                                                    <template x-for="(rating, index) in ratings" :key="index">
                                                        <button type="button" class="btn px-0" @click="selectRating(index)">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star tw-text-orange-400" viewBox="0 0 16 16" x-show="rating['full'] == false">
                                                                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                                                            </svg>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16" x-show="rating['full'] == true">
                                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                            </svg>
                                                        </button>
                                                    </template>
                                                </div>
                                                @error('rating')
                                                    <div class="text-danger small">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <x-main.forms.recaptcha size="normal" />
                                        </div>
                                        <button class="btn btn-primary" type="submit">{{ __('Send Review') }}</button>
                                    </div>
                                </div>
                            </form>
                            <!-- [END] Comment form -->
                        </div>
                    </div>
                    <!-- [END] Reviews -->
    
                </div>
            </div>
        </div>
    </div>

    @push('scriptsBottom')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('comments', () => ({
                    reply(e) {
                        const repliesForm = document.querySelectorAll('div[data-reply-placeholder]');
                        repliesForm.forEach(el => {
                            el.innerHTML = '';
                        });

                        const replyForm = this.$refs.replyForm.innerHTML;
                        const id = e.target.dataset.id;
                        const replyPlaceholder = document.querySelectorAll('div[data-reply-placeholder="' + id + '"]');
                        if (replyPlaceholder.length > 0) {
                            replyPlaceholder[0].innerHTML = replyForm;
                            grecaptcha.render('captchaContainer', {
                                'sitekey': '{{ config('services.grecaptcha.site_key') }}',
                            });
                        }
                    },
                }));

                Alpine.data('rating', () => ({
                    currentStarRating: {!! old('rating') ? "+'" . old('rating') . "'" : 'null' !!},
                    ratings: [
                        { full: false },
                        { full: false },
                        { full: false },
                        { full: false },
                        { full: false },
                    ],
                    init() {
                        if (this.currentStarRating != null) {
                            for (let i = 0; i < this.currentStarRating; i++) {
                                this.ratings[i].full = true;
                            }
                        }
                    },
                    selectRating(index) {
                        for (let i = 0; i < 5; i++) {
                            if (i <= index) {
                                this.ratings[i].full = true;
                                this.currentStarRating = index + 1;
                            }
                            else {
                                this.ratings[i].full = false;
                            }
                        }
                    },
                }));
            });
        </script>
    @endpush
</x-main.layouts.app>