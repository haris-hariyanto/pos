<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Google Places') }}</x-slot>

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

    @foreach ($errors->all() as $message)
    <x-admin.components.alert type="danger">{{ $message }}</x-admin.components.alert>
    @endforeach

    <div class="card">
        <div class="card-body">

            <form action="{{ route('admin.places.find') }}" method="GET">
                <div class="row g-0">
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="place" placeholder="{{ __('Place name') }}" value="{{ request()->query('place') }}">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <select name="country" class="form-control" name="country">
                                <option value="">{{ __('Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->name }}" @selected($country->name == request()->query('country'))>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                        </div>
                    </div>
                </div>
            </form>

            <form action="{{ route('admin.places.find') }}" method="POST" x-data="places">
                @csrf

                <input type="hidden" name="country" value="{{ request()->query('country') }}">
                @if (count($placesList) > 0)
                    <hr>
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkAll" x-model="checkAllStatus" @change="checkAll">
                                <label for="checkAll" class="custom-control-label">{{ __('Check All') }}</label>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                    <hr>
                    @foreach ($placesList as $place)
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="places[]" value="{{ json_encode($place) }}" id="{{ $place['id'] }}" @change="checkPlace">
                                <label for="{{ $place['id'] }}" class="custom-control-label">
                                    <div>
                                        <div class="font-weight-bold">{{ $place['name'] }}</div>
                                        <div class="font-weight-normal">{{ $place['address'] }}</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="checkAllBottom" x-model="checkAllStatus" @change="checkAll">
                                <label for="checkAllBottom" class="custom-control-label">{{ __('Check All') }}</label>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                @endif
            </form>

        </div>
    </div>

    @push('scriptsBottom')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('places', () => ({
                    checkAllStatus: false,
                    checkAll(e) {
                        const currentStatus = e.target.checked;
                        const checkboxes = document.querySelectorAll('input[name="places[]"]');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = currentStatus;
                        });
                    },
                    checkPlace(e) {
                        const checkboxesTotal = document.querySelectorAll('input[name="places[]"]').length;
                        const checkboxesCheckedTotal = document.querySelectorAll('input[name="places[]"]:checked').length;
                        if (checkboxesTotal == checkboxesCheckedTotal) {
                            this.checkAllStatus = true;
                        }
                        else {
                            this.checkAllStatus = false;
                        }
                    },
                }));
            });
        </script>
    @endpush
</x-admin.layouts.app>