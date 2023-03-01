<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Hotels') }}</x-slot>

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
        <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary">{{ __('Add Hotel') }}</a>
    </div>

    <div x-data="modalDelete">
        <div class="card">
            <div class="card-body">
                <div id="toolbar">
                    <button type="button" class="btn btn-danger disabled" data-toggle="modal" data-target="#modalBulkDelete" id="bulkDeleteBtn">{{ __('Delete') }}</button>
                </div>
                <table
                    data-toggle="table"
                    data-url="{{ route('admin.hotels.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-id-field="id"
                    data-select-item-name="hotels"
                    data-toolbar="#toolbar"
                    id="mainTable"
                >
                    <thead>
                        <tr>
                            <th data-checkbox="true"></th>
                            <th data-field="id" data-sortable="true" data-visible="false" data-width="1">{{ __('ID') }}</th>
                            <th data-field="name" data-sortable="true">{{ __('Hotel Name') }}</th>
                            <th data-field="chain" data-visible="false">{{ __('Hotel Chain') }}</th>
                            <th data-field="brand" data-visible="false">{{ __('Brand') }}</th>
                            <th data-field="city" data-visible="true">{{ __('City') }}</th>
                            <th data-field="state" data-visible="true">{{ __('State') }}</th>
                            <th data-field="country" data-visible="true">{{ __('Country') }}</th>
                            <th data-field="continent" data-visible="false">{{ __('Continent') }}</th>
                            <th data-field="total_views" data-sortable="true" data-width="1">{{ __('Total Views') }}</th>
                            <th data-field="weekly_views" data-sortable="true" data-widtg="1">{{ __('Weekly Views') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Hotel?') }}</x-slot>
            <p class="mb-0">{{ __('Delete hotel:') }} <span class="font-weight-bold" x-text="itemName"></span></p>
            <x-slot:modalFooter>
                <form :action="linkDelete" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">{{ __('Delete') }}</button>
                </form>
            </x-slot>
        </x-admin.components.modal>

        <x-admin.components.modal name="bulkDelete">
            <x-slot:modalTitle>{{ __('Delete Hotels?') }}</x-slot:modalTitle>
            <p class="mb-0">{!! __('Delete :count hotels?', ['count' => '<span id="totalHotelsToDelete">0</span>']) !!}</p>
            <x-slot:modalFooter>
                <form action="{{ route('admin.hotels.bulk-delete') }}" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="hotelsIDToDelete" value="">
                    <button class="btn btn-danger" type="submit" id="bulkDeleteSubmit">{{ __('Delete') }}</button>
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
                    const checkboxes = document.querySelectorAll('input[name="hotels"], input[name="btSelectAll"]');
                    const totalHotelsToDelete = document.getElementById('totalHotelsToDelete');

                    checkboxes.forEach(e => {
                        e.addEventListener('change', () => {
                            const checkedCheckboxes = document.querySelectorAll('input[name="hotels"]:checked');
                            if (checkedCheckboxes.length > 0) {
                                bulkDeleteButton.classList.remove('disabled');
                                totalHotelsToDelete.innerHTML = checkedCheckboxes.length;
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
                const hotelsToDelete = [];
                const checkedCheckboxes = document.querySelectorAll('input[name="hotels"]:checked');
                checkedCheckboxes.forEach(e => {
                    hotelsToDelete.push(e.value);
                });
                const JSONHotelsToDelete = JSON.stringify(hotelsToDelete);

                const hotelsField = document.querySelectorAll('input[name="hotelsIDToDelete"]');
                if (hotelsField.length == 1) {
                    hotelsField[0].value = JSONHotelsToDelete;
                }

            });
        </script>
    @endpush
</x-admin.layouts.app>