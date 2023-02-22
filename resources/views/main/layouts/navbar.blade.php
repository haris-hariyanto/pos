<div x-data="autocomplete" x-ref="base" data-base="{{ route('search.autocomplete') }}" x-init="searchQuery = '{{ request()->query('q') }}'">
    <nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
        <div class="container px-4">
    
            <a href="{{ route('index') }}" class="navbar-brand">{{ $settings__website_name }}</a>
    
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a 
                            href="{{ route('index') }}" 
                            @class(['nav-link', 'active' => Route::currentRouteName() === 'index'])
                            @if (Route::currentRouteName() === 'index') aria-current="page" @endif
                        >{{ __('Home') }}</a>
                    </li>
                </ul>
    
                @if (Route::currentRouteName() != 'index')
                    <div class="mx-3 flex-fill d-none d-md-block">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="input-group">
                                <select name="mode" class="form-select" style="flex: 0 1 fit-content;">
                                    <option value="findHotels" @selected(Route::currentRouteName() == 'search.hotels')>{{ __('Find Hotels') }}</option>
                                    <option value="findPlaces" @selected(Route::currentRouteName() == 'search.places')>{{ __('Find Places') }}</option>
                                </select>
                                <div class="form-control z-3 p-0">
                                    <input type="text" class="form-control border-0 rounded-0 w-100 h-100" name="q" placeholder="{{ __('Enter an address or property') }}" value="{{ request()->query('q') }}" data-search="true" x-model="searchQuery" @keyup.debounce="getAutocomplete()">
                                </div>                            
                                <button class="btn btn-secondary px-5" type="submit">{{ __('Search') }}</button>
                            </div>
                        </form>
                    </div>
                @endif
    
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (config('app.open_register'))
                            <li class="nav-item">
                                <a 
                                    href="{{ route('register') }}" 
                                    @class(['nav-link', 'active' => Route::currentRouteName() === 'register'])
                                    @if (Route::currentRouteName() === 'register') aria-current="page" @endif
                                >{{ __('Register') }}</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a 
                                href="{{ route('login') }}" 
                                @class(['nav-link', 'active' => Route::currentRouteName() === 'login'])
                                @if (Route::currentRouteName() === 'login') aria-current="page" @endif
                            >{{ __('Login') }}</a>
                        </li>
                    @endguest
    
                    @auth
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ Auth::user()->username }}</a>
                            <ul class="dropdown-menu bg-white dropdown-menu-end">
                                <li>
                                    <a href="{{ route('admin.index') }}" class="dropdown-item" target="_blank">{{ __('Dashboard') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a href="{{ route('account.account-settings.index') }}" class="dropdown-item">{{ __('Account Settings') }}</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li x-data>
                                    <form action="{{ route('logout') }}" method="POST" x-ref="formLogout">
                                        @csrf
                                        <a href="{{ route('logout') }}" class="dropdown-item" @click.prevent="$refs.formLogout.submit()">{{ __('Logout') }}</a>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
    
        </div>
    </nav>
    
    @if (Route::currentRouteName() != 'index')
        <!-- Search bar mobile -->
        <div class="bg-primary d-block d-md-none">
            <div class="container">
                <form action="{{ route('search') }}" method="GET" class="pb-2 pt-1" x-data="{ searchMode: '{{ Route::currentRouteName() == 'search.places' ? 'findPlaces' : 'findHotels' }}', showSelectMode: false }">
                    <input type="hidden" name="mode" x-model="searchMode">
                    <div class="input-group">
                        <!--
                        <select name="mode" class="form-select" style="flex: 0 1 20%;">
                            <option value="findHotels" @selected(Route::currentRouteName() == 'search.hotels')>{{ __('Hotels') }}</option>
                            <option value="findPlaces" @selected(Route::currentRouteName() == 'search.places')>{{ __('Places') }}</option>
                        </select>
                        -->
                        <input type="text" class="form-control" name="q" placeholder="{{ __('Enter an address or property') }}" value="{{ request()->query('q') }}" data-search="true" x-model="searchQuery" @keyup.debounce="getAutocomplete()" @click="showSelectMode = true">
                        <button class="btn btn-secondary" type="submit">{{ __('Search') }}</button>
                    </div>
                    <div x-show="showSelectMode" x-transition>
                        <div class="mt-2 d-flex align-items-center">
                            <button type="button" class="btn btn-outline-light btn-sm rounded-5 me-1" :class="{ 'active' : searchMode == 'findHotels' }" @click="searchMode = 'findHotels'">{{ __('Hotels') }}</button>
                            <button type="button" class="btn btn-outline-light btn-sm rounded-5" :class="{ 'active' : searchMode == 'findPlaces' }" @click="searchMode = 'findPlaces'">{{ __('Places') }}</button>
                            <button type="button" class="btn btn-link btn-sm text-light" @click="showSelectMode = false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- [END] Search bar mobile -->
    @endif
</div>