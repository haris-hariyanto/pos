<nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
    <div class="container px-4">

        <a href="{{ route('index') }}" class="navbar-brand">{{ config('app.name', 'DNM') }}</a>

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