<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('hotel', [$hotel->slug]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>

        <a href="{{ route('admin.hotels.edit', ['hotel' => $hotel]) }}" class="dropdown-item" class="dropdown-item">{{ __('Edit') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.hotels.destroy', ['hotel' => $hotel]) }}"
            data-name="{{ $hotel->name }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>