<!-- Main sidebar container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand logo -->
    <a href="{{ route('admin.index') }}" class="brand-link">
        <img src="{{ asset('assets/admin/images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name', 'DNM') }}</span>
    </a>
    <!-- [END] Brand logo -->

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.index'])>
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                @can('auth-check', $userAuth->authorize('admin-users-index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.users.index'])>
                            <i class="nav-icon fas fa-user"></i>
                            <p>{{ __('Users') }}</p>
                        </a>
                    </li>
                @endcan

                @can('auth-check', $userAuth->authorize('admin-groups-index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.groups.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.groups.index'])>
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('Groups') }}</p>
                        </a>
                    </li>
                @endcan

                @can('auth-check', $userAuth->authorize('admin-pages-index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.pages.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.pages.index'])>
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('Pages') }}</p>
                        </a>
                    </li>
                @endcan

                @can('auth-check', $userAuth->authorize('admin-contacts-index'))
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.contacts.index'])>
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>{{ __('Messages') }}</p>
                        </a>
                    </li>
                @endcan

                @can('auth-check', $userAuth->authorize('admin-admins-index'))
                    <li class="nav-header">{{ strtoupper(__('Staff')) }}</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.admins.index') }}" @class(['nav-link', 'active' => Route::currentRouteName() == 'admin.admins.index'])>
                            <i class="nav-icon fas fa-lock"></i>
                            <p>{{ __('Administrators') }}</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
        <!-- [END] Sidebar menu -->
    </div>
    <!-- [END] Sidebar -->
</aside>
<!-- [END] Main sidebar container -->