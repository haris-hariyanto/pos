<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Pages Settings') }}</x-slot>
    <div class="row">
        <div class="col-12">

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

            <form action="{{ route('admin.settings.pages') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                        <ul class="nav nav-tabs" role="tablist">
                            @foreach ($pages as $pageKey => $page)
                                <li class="nav-item">
                                    <a href="#setting{{ $pageKey }}" class="nav-link {{ $loop->iteration == 1 ? 'active' : '' }}" data-toggle="pill">{{ __($page['name']) }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            @foreach ($pages as $pageKey => $page)
                                <div id="setting{{ $pageKey }}" class="tab-pane fade {{ $loop->iteration == 1 ? 'active show' : '' }}" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
    
                                            @if (isset($pageExamples[$pageKey]))
                                                <p>{{ __('Page example')  }} : <a href="{{ $pageExamples[$pageKey] }}" target="_blank">{{ $pageExamples[$pageKey] }}</a></p>
                                            @endif
    
                                            @foreach ($page['fields'] as $field)
                                                @if ($field == 'heading')
                                                    <x-admin.forms.input-text :name="'pagesettings_' . $pageKey . '_' . $field" :label="__('Page Title') . ' (<h1>...</h1>)'" value="{{ $pageSettings['pagesettings_' . $pageKey . '_' . $field] }}" />
                                                @endif

                                                @if ($field == 'page_title')
                                                    <x-admin.forms.input-text :name="'pagesettings_' . $pageKey . '_' . $field" :label="__('Page Title') . ' (<title>...</title>)'" value="{{ $pageSettings['pagesettings_' . $pageKey . '_' . $field] }}" />
                                                @endif
    
                                                @if ($field == 'meta_data')
                                                    <x-admin.forms.textarea :name="'pagesettings_' . $pageKey . '_' . $field" :label="__('Meta Data')" rows="15">{{ $pageSettings['pagesettings_' . $pageKey . '_' . $field] }}</x-admin.forms.textarea>
                                                @endif

                                                @if ($field == 'brief_paragraph')
                                                    <x-admin.forms.textarea :name="'pagesettings_' . $pageKey . '_' . $field" :label="__('Brief Paragraph')" rows="5">{{ $pageSettings['pagesettings_' . $pageKey . '_' . $field] }}</x-admin.forms.textarea>
                                                @endif
                                            @endforeach

                                            @if (!empty($page['variables']))
                                                <div class="callout callout-info">
                                                    <div><b>{{ __('Variables') }}</b></div>
                                                    <ul>
                                                        @foreach ($page['variables'] as $variableName => $variableDesc)
                                                            <li><b>{{ $variableName }}</b> &dash; {{ $variableDesc }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
    
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-admin.layouts.app>