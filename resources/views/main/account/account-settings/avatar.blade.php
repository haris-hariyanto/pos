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
                            <div class="col-12 col-lg-6">

                                <div class="mb-3">
                                    <img src="{{ Auth::user()->avatar() }}" alt="{{ Auth::user()->username }}" class="img-fluid rounded" loading="lazy">
                                </div>

                                <form action="{{ route('account.account-settings.avatar.crop') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <input type="file" name="avatar" class="form-control" accept="image/*">
                                    </div>

                                    <button type="submit" class="btn btn-primary">{{ __('Change Avatar') }}</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>