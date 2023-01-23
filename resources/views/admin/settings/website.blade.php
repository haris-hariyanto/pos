<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Website Settings') }}</x-slot>
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

            <form action="{{ route('admin.settings.website') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <b>{{ __('Website Settings') }}</b>
                    </div>
                    <div class="card-body">

                        <x-admin.forms.input-text name="settings__website_name" :label="__('Website Name')" :value="$settings['settings__website_name']" />
                        <x-admin.forms.textarea name="settings__header_script" :label="__('Script before </head>')" rows="5">{{ $settings['settings__header_script'] }}</x-admin.forms.textarea>
                        <x-admin.forms.textarea name="settings__footer_script" :label="__('Script before </html>')" rows="5">{{ $settings['settings__footer_script'] }}</x-admin.forms.textarea>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin.layouts.app>