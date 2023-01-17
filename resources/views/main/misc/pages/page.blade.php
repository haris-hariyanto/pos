<x-main.layouts.app>
    <x-slot:pageTitle>{{ $page->title }}</x-slot>

    @push('metaData')
        {!! $metaData->render() !!}
    @endpush

    <div class="bg-white shadow-sm">
        <div class="container py-2 px-4">

            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <!-- Breadcrumb -->
                    <div class="mt-2">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('index') }}">{{ __('Home') }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $page->title }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <!-- [END] Breadcrumb -->

                    <h1 class="fs-2 mb-2">{{ $page->title }}</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="p-1">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
    
                    <div class="card shadow-sm">
                        <div class="card-body">
                            {!! $page->content() !!}
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
</x-main.layouts.app>