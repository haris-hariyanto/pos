<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Pages Settings') }}</x-slot>
    <div class="row">
        <div class="col-12 col-lg-6">

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

            <form action="{{ route('admin.settings.page-setting', [$key]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Page Settings') }} : {{ $key }}</b>
                    </div>
                    <div class="card-body">
                        @foreach ($pageSettings['fields'] as $field)
                            <x-admin.forms.input-text :name="$field['name']" :label="__($field['friendly_name'])" value="" />
                        @endforeach
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>