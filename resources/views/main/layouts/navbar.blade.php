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
                            <input type="text" class="form-control flex-fill" name="q" placeholder="{{ __('Enter an address or property') }}" value="{{ request()->query('q') }}">
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
            <form action="{{ route('search') }}" method="GET" class="pb-2 pt-1">
                <div class="input-group">
                    <select name="mode" class="form-select" style="flex: 0 1 fit-content;">
                        <option value="findHotels" @selected(Route::currentRouteName() == 'search.hotels')>{{ __('Find Hotels') }}</option>
                        <option value="findPlaces" @selected(Route::currentRouteName() == 'search.places')>{{ __('Find Places') }}</option>
                    </select>
                    <input type="text" class="form-control" name="q" placeholder="{{ __('Enter an address or property') }}" value="{{ request()->query('q') }}">
                    <button class="btn btn-secondary">{{ __('Search') }}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- [END] Search bar mobile -->
@endif