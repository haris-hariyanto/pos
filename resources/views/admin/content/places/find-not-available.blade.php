<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Google Places') }}</x-slot>

    <div class="card">
        <div class="card-body">
            <p class="mb-0">
                <code>GOOGLE_PLACE_API_KEY</code> di file <code>.env</code> harus diisi!
            </p>
        </div>
    </div>
</x-admin.layouts.app>