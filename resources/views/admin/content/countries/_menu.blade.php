<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('country', [$country->slug]) }}" class="dropdown-item" target="_blank">{{ __('Open Page') }}</a>

        <a href="{{ route('admin.countries.edit', ['country' => $country]) }}" class="dropdown-item">{{ __('Edit') }}</a>

        <button
            type="button"
            class="dropdown-item"
            data-toggle="modal"
            data-target="#modalDelete"
            data-link-delete="{{ route('admin.countries.destroy', ['country' => $country]) }}"
            data-name="{{ $country->name }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>