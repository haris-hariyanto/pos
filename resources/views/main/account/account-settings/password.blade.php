<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Account Settings') }} - {{ __('Password') }}</x-slot>

    <div class="container">
        <h1 class="fs-3 mb-3 mt-3">{{ __('Account Settings') }}</h1>

        @if (session('success'))
            <x-main.components.alert class="mb-3">
                {{ session('success') }}
            </x-main.components.alert>
        @endif

        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-5 col-lg-3">
                @include('main.account.account-settings._sidebar')
            </div>
            <div class="col-12 col-sm-10 col-md-7 col-lg-9">
                <div class="card">
                    <div class="card-header">{{ __('Change Password') }}</div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <form action="{{ route('account.account-settings.password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <x-main.forms.password name="current_password" :label="__('Current password')" />
                                    <x-main.forms.password name="password" :label="__('New password')" />
                                    <x-main.forms.password name="password_confirmation" :label="__('Confirm new password')" />

                                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>