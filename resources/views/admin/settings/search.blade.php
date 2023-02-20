<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Search Settings') }}</x-slot:pageTitle>
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

            <form action="{{ route('admin.settings.search') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Search Settings') }}</b>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="font-weight-bold">{{ __('Enable search using the Google Places API') }}</div>
                            <x-admin.forms.radio :label="__('Yes')" name="searchsettings__enabled" value="Y" :selected="$settings['searchsettings__enabled'] == 'Y'" />
                            <x-admin.forms.radio :label="__('No')" name="searchsettings__enabled" value="N" :selected="$settings['searchsettings__enabled'] == 'N'" />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>