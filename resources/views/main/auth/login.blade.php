<x-main.layouts.app>
    <x-slot:pageTitle>{{ __('Login') }}</x-slot>

    <div class="container py-4">
        <div class="row g-2 justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">

                @if (session('status'))
                    <div class="alert alert-success mb-2">{{ session('status') }}</div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="fs-2">{{ __('Login') }}</h1>

                        <form action="{{ route('login') }}" method="POST">
                            @csrf

                            <x-main.forms.input-text name="email" :label="__('Email') . ' / ' . __('Username')" />
                            <x-main.forms.password name="password" :label="__('Password')" />

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" id="checkboxRemember" class="form-check-input" @checked(old() ? old('remember') : true)>
                                    <label for="checkboxRemember" class="form-check-label">{{ __('Remember me') }}</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-main.layouts.app>