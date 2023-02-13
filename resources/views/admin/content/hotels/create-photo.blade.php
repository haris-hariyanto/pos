<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Add Photos') }}</x-slot:pageTitle>

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

    <div class="row">
        <div class="col-12 col-lg-6">

            <form action="{{ route('admin.hotels.update-cover', ['hotel' => $hotel]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="source" value="create">
            
                <div class="card" x-data="photos">
                    <div class="card-header font-weight-bold">{{ __('Photos') }}</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <x-admin.forms.radio :label="__('Hotlink')" name="photosType" value="hotlink" x-model="currentMode" :selected="true" @change="changeMode('hotlink')" />
                            <x-admin.forms.radio :label="__('Upload')" name="photosType" value="upload" x-model="currentMode" @change="changeMode('upload')" />
                        </div>

                        <div x-show="currentMode == 'hotlink'">
                            @for ($i = 0; $i < 5; $i++)
                                <x-admin.forms.input-text-array :label="__('Photo') . ' ' . ($i + 1)" name="photos_hotlinks" index="{{ $i }}" :value="old('photos_hotlinks.' . $i)" />
                            @endfor
                        </div>
    
                        <div x-show="currentMode == 'upload'">
                            @for ($i = 0; $i < 5; $i++)
                                <x-admin.forms.file-array :label="__('Photo') . ' ' . ($i + 1)" name="photos_uploads" index="{{ $i }}" value="" />
                            @endfor
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save Photos') }}</button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    @push('scriptsBottom')
        <script src="{{ asset('assets/admin/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('photos', () => ({
                    currentMode: {!! session('tab') == 'upload' || old('photosType') == 'upload' ? "'upload'" : "'hotlink'" !!},
                    changeMode(mode) {
                        this.currentMode = mode;
                    },
                }));
            });

            $(function () {
                bsCustomFileInput.init();
            });
        </script>
    @endpush
</x-admin.layouts.app>