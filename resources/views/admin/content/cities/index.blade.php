<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Cities') }}</x-slot>

    @if (session('success'))
        <x-admin.components.alert>
            {{ session('success') }}
        </x-admin.components.alert>
    @endif

    @if (session('error'))
        <x-admin.components.alert type="danger">
            {{ session('error') }}
        </x-admin.components.alert>
    @endif

    <div class="card">
        <div class="card-body">
            <table
                data-toggle="table"
                data-url="{{ route('admin.cities.index.data') }}"
                data-pagination="true"
                data-side-pagination="server"
                data-search="true"
                data-show-columns="true"
                data-show-columns-toggle-all="true"
            >
                <thead>
                    <tr>
                        <th data-field="id" data-sortable="true" data-visible="false" data-width="1">{{ __('ID') }}</th>
                        <th data-field="name" data-sortable="true">{{ __('City Name') }}</th>
                        <th data-field="state" data-visible="true">{{ __('State') }}</th>
                        <th data-field="country" data-visible="true">{{ __('Country') }}</th>
                        <th data-field="continent" data-visible="false">{{ __('Continent') }}</th>
                        <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.css') }}">
    @endpush

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
    @endpush
</x-admin.layouts.app>