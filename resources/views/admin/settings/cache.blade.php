<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Cache') }}</x-slot>
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

            <form action="{{ route('admin.settings.cache') }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Flush Cache') }}</b>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-danger">{{ __('Flush Cache') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>