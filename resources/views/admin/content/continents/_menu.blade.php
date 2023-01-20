<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('continent', [$continent->slug]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.continents.destroy', ['continent' => $continent]) }}"
            data-name="{{ $continent->name }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>