<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Edit Country') }}</x-slot>
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

            <form action="{{ route('admin.countries.update', ['country' => $country]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="alert alert-info">{{ __('Delete cache manually if there is no change after updating') }}</div>

                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.input-text name="name" :label="__('Country')" :value="$country->name" />

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>