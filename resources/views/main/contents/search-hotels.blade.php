<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Search Result : :q', ['q' => $query]) }}</x-slot>

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">
            <h1 class="fs-2 mb-3 mt-3">{{ __('Search Result : :q', ['q' => $query]) }}</h1>
        </div>
    </div>

    <div class="p-1">
        <div class="container py-4">
            <div class="row g-2 justify-content-center">
                <div class="col-12 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div>
                                <div class="fw-bold mb-1">{{ __('Star Rating') }}</div>
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-2" type="checkbox" value="{{ $i }}">
                                        <label class="form-check-label">
                                            @for ($star = 1; $star <= $i; $star++)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill tw-text-orange-400" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                                </svg>
                                            @endfor
                                        </label>
                                    </div>
                                @endfor
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="fw-bold mb-1">{{ __('Price Range') }}</div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="small text-muted mb-1">{{ __('Min') }}</div>
                                        <div>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted mb-1 text-end">{{ __('Max') }}</div>
                                        <div>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-secondary">{{ __('Apply') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-9">
                    @foreach ($resultsArray as $result)
                        <x-main.components.contents.hotel :hotel="$result" :show-address="true" />
                    @endforeach
                    <div class="my-2">
                        {{ $results->links('components.main.components.simple-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>