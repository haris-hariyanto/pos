<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Account Settings') }} - {{ __('Avatar') }}</x-slot>

    <div class="container">
        <h1 class="fs-3 mb-3 mt-3">{{ __('Account Settings') }}</h1>

        @if (session('success'))
            <x-main.components.alert class="mb-3">
                {{ session('success') }}
            </x-main.components.alert>
        @endif

        @if ($errors->any())
            <x-main.components.alert class="mb-3" type="danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-main.components.alert>
        @endif

        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-5 col-lg-3">
                @include('main.account.account-settings._sidebar')
            </div>
            <div class="col-12 col-sm-10 col-md-7 col-lg-9">
                <div class="card">
                    <div class="card-header">{{ __('Change Avatar') }}</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 text-center" x-data="cropAvatar">

                                <form action="{{ route('account.account-settings.avatar.update') }}" method="POST" id="changeAvatarForm">
                                    @csrf

                                    <div class="mb-3">
                                        <div id="cropAvatar"></div>
                                    </div>

                                    <input type="hidden" name="avatar" value="{{ $imageKey }}">
                                    <input type="hidden" id="avatarPoints" name="points" value="0,0,0,0">

                                    <button type="submit" class="btn btn-primary" @click.prevent="submit()">{{ __('Save') }}</button>

                                    <div class="mt-3">
                                        <a href="{{ route('account.account-settings.avatar.edit') }}">{{ __('Cancel') }}</a>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="{{ asset('assets/main/plugins/croppie/croppie.css') }}">
        <script src="{{ asset('assets/main/plugins/croppie/croppie.min.js') }}"></script>
    @endpush

    @push('scriptsBottom')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('cropAvatar', () => ({
                    croppie: false,
                    init() {
                        this.croppie = new Croppie(document.getElementById('cropAvatar'), {
                            viewport: {
                                width: 200,
                                height: 200,
                            },
                            boundary: {
                                width: 300,
                                height: 300,
                            },
                        });
                        this.croppie.bind({
                            url: '{{ $image }}',
                        });
                    },
                    submit() {
                        if (this.croppie) {
                            const result = this.croppie.get();
                            const avatarPoints = result.points.toString();
                            document.getElementById('avatarPoints').setAttribute('value', avatarPoints);
                        }
                        document.getElementById('changeAvatarForm').submit();
                    },
                }));
            });
        </script>
    @endpush
</x-main.layouts.app>