<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Register') }}</x-slot>

    <div class="bg-white shadow-sm">
        <div class="container py-5 px-3">
            <div class="row justify-content-start">
                <div class="col-12 col-lg-8">
                    <h1>{{ config('app.name') }}</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloribus ut, sequi accusamus porro nesciunt deleniti corporis quidem nisi dolorum aliquam?</p>
                    <div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="{{ __('Enter place or address') }}">
                            <button class="btn btn-primary" type="submit">{{ __('Search') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-4">
        <h2 class="fs-3 mb-3">{{ __('Find Hotels' )}}</h2>

        <div class="row g-2 tw-justify-center">
            @foreach ($continents as $continent)
                <div class="col-12 col-lg-4 col-xl-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="mb-3">
                                <img src="https://cdn.pixabay.com/photo/2022/12/23/16/03/sunrise-7674594_960_720.jpg" alt="{{ $continent->name }}" class="img-fluid rounded" loading="lazy">
                            </div>
                            <h3 class="fs-5 mb-2">{{ $continent->name }}</h3>
                            <div class="row">
                                @foreach ($continent->countries()->take(8)->get() as $country)
                                    <div class="col-6 tw-line-clamp-1 mb-1">
                                        <a href="#">{{ $country->name }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer text-end py-2">
                            <a href="#" class="btn btn-outline-primary btn-sm">{{ __('More Countries') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container py-4">
        <div class="row g-2">
            <div class="col-12 col-lg-6">
                <h2 class="fs-3 mb-3">{{ __('Popular Places') }}</h2>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($popularPlaces as $popularPlace)
                                <div class="col-12 col-lg-6">
                                    <div class="mb-2">
                                        <div class="tw-line-clamp-1"><a href="#">{{ $popularPlace->name }}</a></div>
                                        <div class="tw-line-clamp-1">{{ $popularPlace->country }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <h2 class="fs-3 mt-2 mt-lg-0 mb-3">{{ __('Popular Hotels') }}</h2>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($popularHotels as $popularHotel)
                                <div class="col-12 col-lg-6">
                                    <div class="mb-2">
                                        <div class="tw-line-clamp-1"><a href="#">{{ $popularHotel->name }}</a></div>
                                        <div class="tw-line-clamp-1">{{ $popularHotel->country }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>