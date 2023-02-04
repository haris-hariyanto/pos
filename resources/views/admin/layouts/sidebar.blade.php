<!-- Main sidebar container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand logo -->
    <a href="{{ route('admin.index') }}" class="brand-link">
        <img src="{{ asset('assets/admin/images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $settings__website_name }}</span>
    </a>
    <!-- [END] Brand logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!--
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.index'])>
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                -->

                <li class="nav-header">{{ strtoupper(__('Contents')) }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.hotels.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.hotels.index'])>
                        <i class="nav-icon fas fa-hotel"></i>
                        <p>{{ __('Hotels') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.reviews.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.reviews.index'])>
                        <i class="nav-icon fas fa-comments"></i>
                        <p>{{ __('Reviews') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.places.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.places.index'])>
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>{{ __('Places') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.cities.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.cities.index'])>
                        <i class="nav-icon fas fa-city"></i>
                        <p>{{ __('Cities') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.states.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.states.index'])>
                        <i class="nav-icon fas fa-city"></i>
                        <p>{{ __('States') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.countries.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.countries.index'])>
                        <i class="nav-icon fas fa-flag"></i>
                        <p>{{ __('Countries') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.continents.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.continents.index'])>
                        <i class="nav-icon fas fa-globe-asia"></i>
                        <p>{{ __('Continents') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ strtoupper(__('Settings')) }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.cover') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.cover'])>
                        <i class="nav-icon fas fa-images"></i>
                        <p>{{ __('Cover Images') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.website') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.website'])>
                        <i class="nav-icon fas fa-file"></i>
                        <p>{{ __('Website') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.pages') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.pages'])>
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>{{ __('Pages Settings') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.settings.cache') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.settings.cache'])>
                        <i class="nav-icon fas fa-bolt"></i>
                        <p>{{ __('Cache') }}</p>
                    </a>
                </li>

                <li class="nav-header">{{ strtoupper(__('Others')) }}</li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.users.index'])>
                        <i class="nav-icon fas fa-user"></i>
                        <p>{{ __('Users') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.pages.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.pages.index'])>
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>{{ __('Pages') }}</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.contacts.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.contacts.index'])>
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>{{ __('Messages') }}</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- [END] Sidebar menu -->
    </div>
    <!-- [END] Sidebar -->
</aside>
<!-- [END] Main sidebar container -->