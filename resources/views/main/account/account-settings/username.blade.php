<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Account Settings') }} - {{ __('Username') }}</x-slot>

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
                    <div class="card-header">{{ __('Change Username') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <form action="{{ route('account.account-settings.username.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <x-main.forms.input-text name="username" :label="__('Username')" value="{{ old('username') ?? $user->username }}" />
                                    
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