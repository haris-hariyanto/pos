<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Add Place') }}</x-slot>

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

    <form action="{{ route('admin.places.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-12 col-lg-6">

                <div class="card">
                    <div class="card-body">

                        <x-admin.forms.input-text name="name" :label="__('Name')" :value="old('name')" />
                        <x-admin.forms.textarea name="address" :label="__('Address')" rows="2">{{ old('address') }}</x-admin.forms.textarea>
                        <div class="row g-0">
                            <div class="col-6">
                                <x-admin.forms.input-text name="longitude" :label="__('Longitude')" />
                            </div>
                            <div class="col-6">
                                <x-admin.forms.input-text name="latitude" :label="__('Latitude')" />
                            </div>
                        </div>
                        <x-admin.forms.select :label="__('Country')" :options="$countries" name="country" :selected="old('country')" />
                        <x-admin.forms.select :label="__('Category')" :options="$categories" name="category" :selected="old('category')" />

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</x-admin.layouts.app>