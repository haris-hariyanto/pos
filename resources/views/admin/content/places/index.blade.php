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
                <div id="toolbar">
                    <button type="button" class="btn btn-danger disabled" data-toggle="modal" data-target="#modalBulkDelete" id="bulkDeleteBtn">{{ __('Delete') }}</button>
                </div>
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.places.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-id-field="id"
                    data-select-item-name="places"
                    data-toolbar="#toolbar"
                    id="mainTable"
                >
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
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

        <x-admin.components.modal name="bulkDelete">
            <x-slot:modalTitle>{{ __('Delete Places?') }}</x-slot:modalTitle>
            <p class="mb-0">{!! __('Delete :count places?', ['count' => '<span id="totalPlacesToDelete"></span>']) !!}</p>
            <x-slot:modalFooter>
                <form action="{{ route('admin.places.bulk-delete') }}" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="placesIDToDelete" value="">
                    <button class="btn btn-danger" type="submit" id="bulkDeleteSubmit">{{ __('Submit') }}</button>
                </form>
            </x-slot:modalFooter>
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

            $('#mainTable').bootstrapTable({
                onPostBody: function () {
                    const bulkDeleteButton = document.getElementById('bulkDeleteBtn');
                    const checkboxes = document.querySelectorAll('input[name="places"], input[name="btSelectAll"]');
                    const totalPlacesToDelete = document.getElementById('totalPlacesToDelete');

                    checkboxes.forEach(e => {
                        e.addEventListener('change', () => {
                            const checkedCheckboxes = document.querySelectorAll('input[name="places"]:checked');
                            if (checkedCheckboxes.length > 0) {
                                bulkDeleteButton.classList.remove('disabled');
                                totalPlacesToDelete.innerHTML = checkedCheckboxes.length;
                            }
                            else {
                                bulkDeleteButton.classList.add('disabled');
                            }
                        });
                    });
                },
            });

            const bulkDeleteSubmit = document.getElementById('bulkDeleteSubmit');
            const formDelete = document.getElementById('formDelete');
            bulkDeleteSubmit.addEventListener('click', (e) => {
                const placesToDelete = [];
                const checkedCheckboxes = document.querySelectorAll('input[name="places"]:checked');
                checkedCheckboxes.forEach(e => {
                    placesToDelete.push(e.value);
                });
                const JSONPlacesToDelete = JSON.stringify(placesToDelete);

                const placesField = document.querySelectorAll('input[name="placesIDToDelete"]');
                if (placesField.length == 1) {
                    placesField[0].value = JSONPlacesToDelete;
                }

            });
        </script>
    @endpush
</x-admin.layouts.app>