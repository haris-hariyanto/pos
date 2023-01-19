<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit Page') }}</x-slot>

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

    <form action="{{ route('admin.pages.update', ['page' => $page]) }}" method="POST" x-data="form" @submit.prevent="submit">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12 col-lg-9">
                <div class="card">
                    <div class="card-body">

                        @if ($page->status == 'DRAFT')
                            <div class="mb-2">
                                <span class="badge badge-secondary">{{ __('Draft') }}</span>
                            </div>
                        @endif

                        <x-admin.forms.input-text name="page_title" :label="__('Page title')" :value="old('page_title') ?? $page->title" />
                        <x-admin.forms.textarea name="page_content" :label="__('Page content')">{{ old('page_content') ?? $page->content }}</x-admin.forms.textarea>
                        <input type="hidden" name="status" x-model="status">

                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block" @click="saveMode('PUBLISHED')">{{ __('Save') }}</button>
                        <button type="submit" class="btn btn-outline-primary btn-block" @click="saveMode('DRAFT')">{{ __('Save as Draft') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @push('scriptsBottom')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('form', () => ({
                    status: '{{ $page->status }}',
                    submit(e) {
                        e.target.submit();
                    },
                    saveMode(mode) {
                        this.status = mode;
                    },
                }));
            });
        </script>
    @endpush
</x-admin.layouts.app>