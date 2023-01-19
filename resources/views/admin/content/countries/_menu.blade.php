<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('country', [$country->slug]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>
    </div>
</div>