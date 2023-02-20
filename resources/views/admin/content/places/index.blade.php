<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Places') }}</x-slot>

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

    <div class="d-flex justify-content-end mb-3">
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Add Place') }}</button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{ route('admin.places.create') }}">{{ __('Manual') }}</a>
                <a class="dropdown-item" href="{{ route('admin.places.find') }}">{{ __('Google Places') }}</a>
            </div>
        </div>
    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.places.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="false" data-width="1">{{ __('ID') }}</th>
                            <th data-field="name" data-sortable="true">{{ __('Place Name') }}</th>
                            <th data-field="address" data-visible="true">{{ __('Address') }}</th>
                            <th data-field="country" data-visible="true">{{ __('Country') }}</th>
                            <th data-field="continent" data-visible="false">{{ __('Continent') }}</th>
                            <th data-field="total_views" data-sortable="true" data-width="1">{{ __('Total Views') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Place?') }}</x-slot>
            <p class="mb-0">{{ __('Delete place:') }} <span class="font-weight-bold" x-text="itemName"></span></p>
            <x-slot:modalFooter>
                <form :action="linkDelete" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                </form>
            </x-slot>
        </x-admin.components.modal>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.css') }}">
    @endpush

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('modalDelete', () => ({
                    linkDelete: false,
                    itemName: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                        this.itemName = e.target.dataset.name;
                    },
                }));
            });
        </script>
    @endpush
</x-admin.layouts.app>