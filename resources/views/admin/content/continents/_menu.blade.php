<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('continent', [$continent->slug]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>
    </div>
</div>