<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Menu') }}</button>
    <div class="dropdown-menu">
        <a href="{{ route('admin.users.edit', ['user' => $user]) }}" class="dropdown-item">{{ __('Edit') }}</a>

        <a href="{{ route('admin.users.password.edit', ['user' => $user]) }}" class="dropdown-item">{{ __('Edit Password') }}</a>

        <button 
            type="button" 
            class="dropdown-item" 
            data-toggle="modal" 
            data-target="#modalDelete" 
            data-link-delete="{{ route('admin.users.destroy', ['user' => $user]) }}" 
            data-username="{{ $user->username }}"
            @click="deleteItem"
        >{{ __('Delete') }}</button>
    </div>
</div>