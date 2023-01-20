<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('hotel.location', ['state', $state->slug]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.states.destroy', ['state' => $state]) }}"
            data-name="{{ $state->name }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>