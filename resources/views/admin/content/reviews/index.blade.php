<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Reviews') }}</x-slot>

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

    <div x-data="item">
        <div class="card">
            <div class="card-body">

                <div id="toolbar" class="d-flex align-items-center">
                    <div class="dropdown" x-data="{ type: 'all', typeName: '{{ __('All') }}' }" :data-type="type" id="filterRows" x-init="$watch('type', refreshTable)">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" x-text="typeName"></button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" @click="type = 'all'; typeName = '{{ __('All') }}'">{{ __('All') }}</button>
                            <button class="dropdown-item" type="button" @click="type = 'reviews'; typeName = '{{ __('Reviews') }}'">{{ __('Reviews') }}</button>
                            <button class="dropdown-item" type="button" @click="type = 'replies'; typeName = '{{ __('Replies') }}'">{{ __('Replies') }}</button>
                        </div>
                    </div>

                    <a href="{{ route('admin.reviews.approve_all') }}" class="btn btn-secondary ml-2">{{ __('Approve All') }}</a>
                </div>

                <table
                    id="mainTable"
                    data-toggle="table"
                    data-url="{{ route('admin.reviews.index.data') }}"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-search="true"
                    data-show-columns="true"
                    data-show-columns-toggle-all="true"
                    data-row-style="rowStyle"
                    data-row-attributes="rowAttributes"
                    data-toolbar="#toolbar"
                    data-query-params="filterRows"
                >
                    <thead>
                        <tr>
                            <th data-field="id" data-sortable="true" data-visible="false" data-width="1">{{ __('ID') }}</th>
                            <th data-field="name" data-sortable="true">{{ __('Name') }}</th>
                            <th data-field="review" class="w-50">{{ __('Review') }}</th>
                            <th data-field="rating" data-sortable="true">{{ __('Rating') }}</th>
                            <th data-field="hotel">{{ __('Hotel') }}</th>
                            <th data-field="time" data-sortable="true">{{ __('Created at') }}</th>
                            <th data-field="menu" data-align="center" data-switchable="false" data-width="1">{{ __('Menu') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <x-admin.components.modal name="delete">
            <x-slot:modalTitle>{{ __('Delete Review?') }}</x-slot>
            <p class="mb-0">{{ __('Delete review') }}</p>
            <x-slot:modalFooter>
                <form :action="linkDelete" method="POST" id="formDelete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </x-slot>
        </x-admin.components.modal>

        <form :action="linkReply" method="POST">
            @csrf
            <x-admin.components.modal name="reply">
                <x-slot:modalTitle>{{ __('Reply') }}</x-slot:modalTitle>
                <div>
                    <x-admin.forms.textarea name="reply" :label="__('Reply')" rows="5"></x-admin.forms.textarea>
                </div>
                <x-slot:modalFooter>
                    <button class="btn btn-primary" type="submit" x-text="btnText"></button>
                </x-slot:modalFooter>
            </x-admin.components.modal>
        </form>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.css') }}">
    @endpush

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/plugins/bootstrap-table/bootstrap-table.min.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('item', () => ({
                    linkDelete: false,
                    deleteItem(e) {
                        this.linkDelete = e.target.dataset.linkDelete;
                    },

                    btnText: '',
                    linkReply: false,
                    sendReply(e) {
                        this.btnText = e.target.dataset.btnText;
                        this.linkReply = e.target.dataset.linkReply;
                    },

                    approveReview(e) {
                        const linkApprove = e.target.dataset.linkApprove;
                        const reviewID = e.target.dataset.id;

                        const el = document.querySelectorAll('tr[data-id="' + reviewID + '"]');
                        if (el.length == 1) {
                            axios({
                                method: 'PUT',
                                url: linkApprove,
                            })
                                .then(response => {
                                    const responseData = response.data;
                                    const responseStatus = response.status;

                                    if (responseStatus == '200' && responseData.success == true) {
                                        const btnApprove = document.querySelectorAll('button[data-link-approve][data-id="' + reviewID + '"]');
                                        if (btnApprove.length == 1) {
                                            btnApprove[0].classList.add('d-none');
                                            btnApprove[0].classList.remove('d-block');
                                        }

                                        const btnUnapprove = document.querySelectorAll('button[data-link-unapprove][data-id="' + reviewID + '"]');
                                        if (btnUnapprove.length == 1) {
                                            btnUnapprove[0].classList.add('d-block');
                                            btnUnapprove[0].classList.remove('d-none');
                                        }

                                        el[0].classList.remove('table-warning');

                                        const newReviewsBadge = document.getElementById('newReviewsBadge');
                                        newReviewsBadge.innerHTML = responseData.new;
                                    }
                                });
                        }
                    },

                    unapproveReview(e) {
                        const linkUnapprove = e.target.dataset.linkUnapprove;
                        const reviewID = e.target.dataset.id;
                        
                        const el = document.querySelectorAll('tr[data-id="' + reviewID + '"]');
                        if (el.length == 1) {
                            axios({
                                method: 'PUT',
                                url: linkUnapprove,
                            })
                                .then(response => {
                                    const responseData = response.data;
                                    const responseStatus = response.status;

                                    if (responseStatus == '200' && responseData.success == true) {
                                        const btnApprove = document.querySelectorAll('button[data-link-approve][data-id="' + reviewID + '"]');
                                        if (btnApprove.length == 1) {
                                            btnApprove[0].classList.add('d-block');
                                            btnApprove[0].classList.remove('d-none');
                                        }

                                        const btnUnapprove = document.querySelectorAll('button[data-link-unapprove][data-id="' + reviewID + '"]');
                                        if (btnUnapprove.length == 1) {
                                            btnUnapprove[0].classList.add('d-none');
                                            btnUnapprove[0].classList.remove('d-block');
                                        }

                                        el[0].classList.add('table-warning');

                                        const newReviewsBadge = document.getElementById('newReviewsBadge');
                                        newReviewsBadge.innerHTML = responseData.new;
                                    }
                                });
                        }
                    },
                }));
            });

            function rowStyle(row, index) {
                let classes = '';
                if (row.is_accepted_raw == 'N') {
                    classes = 'table-warning';
                }
                return {
                    classes: classes,
                };
            }

            function rowAttributes(row, index) {
                return {
                    'data-id': row.id,
                };
            }

            function filterRows(params) {
                const type = document.getElementById('filterRows').dataset.type;
                params.type = type;
                return params;
            }

            function refreshTable() {
                $(document).ready(function () {
                    const table = $('#mainTable');
                    table.bootstrapTable('refresh');
                });
            }
        </script>
    @endpush
</x-admin.layouts.app>