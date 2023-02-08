<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Search Result : :q', ['q' => $query]) }}</x-slot>

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">
            <h1 class="fs-2 mb-3 mt-3">{{ __('Search Result : :q', ['q' => $query]) }}</h1>
        </div>
    </div>

    <div class="p-1">
        <div class="container py-4">
            <div class="row g-2 justify-content-center" x-data="listing">
                <div class="col-12 col-lg-3">

                    <!-- Filter -->
                    <div class="d-flex d-lg-none">
                        <button type="button" class="btn btn-secondary" @click="toggleFilterSection()">
                            <div class="d-flex align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
                                </svg>
                                <div class="ms-2">{{ __('Filter') }}</div>
                            </div>
                        </button>
                        <div class="dropdown ms-2">
                            <button class="btn btn-secondary dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Sort') }} : <span x-text="currentSortModeText"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="recommended" @click="changeSortMode('recommended')">{{ __('Recommended') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="lowest-price" @click="changeSortMode('lowest-price')">{{ __('Lowest Price') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="highest-price" @click="changeSortMode('highest-price')">{{ __('Highest Price') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-2 mt-lg-0 card shadow-sm d-none d-lg-block" :class="{ 'd-none': !showFilterSection }" data-base="{{ route('search.hotels', ['q' => $query]) }}" x-ref="base">
                        <div class="card-body">
                            <div>
                                <div class="fw-bold mb-1">{{ __('Hotel Star') }}</div>

                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2" type="checkbox" id="filterStarUnrated" value="unrated" @change="changeFilter()" x-model="dataFilterStar">
                                    <label class="form-check-label" for="filterStarUnrated">{{ __('Unrated') }}</label>
                                </div>

                                @for ($i = 1; $i <= 5; $i++)
                                    <div class="form-check d-flex align-items-center">
                                        <input class="form-check-input me-2" type="checkbox" id="filterStar{{ $i }}" value="{{ $i }}" @change="changeFilter()" x-model="dataFilterStar">
                                        <label class="form-check-label" for="filterStar{{ $i }}">
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

                            <div>
                                <div class="fw-bold mb-1">{{ __('Price Range') }}</div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="small text-muted mb-1">{{ __('Min') }}</div>
                                        <div>
                                            <input type="text" class="form-control" x-model="dataFilterMinPrice" @keyup.debounce.1000ms="changeFilter()">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted mb-1 text-end">{{ __('Max') }}</div>
                                        <div>
                                            <input type="text" class="form-control" x-model="dataFilterMaxPrice" @keyup.debounce.1000ms="changeFilter()">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [END] Filter -->

                </div>
                <div class="col-12 col-lg-9">
                    <!-- Sort -->
                    <div class="mb-2 d-none d-lg-block">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Sort') }} : <span x-text="currentSortModeText"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="recommended" @click="changeSortMode('recommended')">{{ __('Recommended') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="lowest-price" @click="changeSortMode('lowest-price')">{{ __('Lowest Price') }}</a>
                                </li>
                                <li>
                                    <a href="#" class="dropdown-item" data-sort-by="highest-price" @click="changeSortMode('highest-price')">{{ __('Highest Price') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- [END] Sort -->

                    <div x-ref="mainResults">
                        @forelse ($resultsArray as $result)
                            <x-main.components.contents.hotel :hotel="$result" :show-address="true" />
                        @empty
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p class="mb-0">{{ __('No hotels available.') }}</p>
                                </div>
                            </div>
                        @endforelse
                        <div class="my-2">
                            {{ $results->links('components.main.components.simple-pagination') }}
                        </div>
                    </div>
                    <div x-show="isLoading">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-center">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-show="!isLoading" x-html="results"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scriptsBottom')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('listing', () => ({
                    countRequest: 0,
                    dataFilterStar: {{ Js::from(explode(',', request()->query('star', null))) }},
                    dataFilterMinPrice: {!! request()->query('min-price', "''") !!},
                    dataFilterMaxPrice: {!! request()->query('max-price', "''") !!},
                    changeFilter() {
                        this.countRequest++;
                        this.results = '';
                        if (this.$refs.mainResults) {
                            this.$refs.mainResults.remove();
                        }
                        this.isLoading = true;
                        this.getResults();
                    },
                    getResults() {
                        const baseURL = this.$refs.base.dataset.base;
                        const currentRequest = this.countRequest;
                        
                        axios({
                            method: 'GET',
                            url: baseURL,
                            params: {
                                star: this.dataFilterStar.join(','),
                                'min-price': this.dataFilterMinPrice,
                                'max-price': this.dataFilterMaxPrice,
                                'sort-by': this.currentSortMode,
                            },
                        })
                            .then(response => {
                                const responseData = response.data;
                                const responseStatus = response.status;

                                if (responseStatus == '200' && responseData.success == true) {
                                    if (currentRequest == this.countRequest) {
                                        this.isLoading = false;
                                        this.results = responseData.results;
                                    }
                                }
                            });
                    },
                    results: '',
                    isLoading: false,

                    currentSortMode: 'recommended',
                    get currentSortModeText()
                    {
                        const sortMode = document.querySelectorAll('[data-sort-by="' + this.currentSortMode + '"]');
                        return sortMode[0].innerHTML;
                    },
                    changeSortMode(sortMode)
                    {
                        this.currentSortMode = sortMode;
                        this.changeFilter();
                    },

                    showFilterSection: false,
                    toggleFilterSection() {
                        this.showFilterSection = !this.showFilterSection;
                    },
                }));
            });
        </script>
    @endpush
</x-main.layouts.app>