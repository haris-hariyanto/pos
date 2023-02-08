{{-- Data Properties ['label'] Attributes ['name'] --}}

@props(['label'])

<div class="form-group">
    <label for="file{{ Str::studly($attributes->get('name')) }}">{{ $label }}</label>
    <div class="custom-file">
        <input
            {{ $attributes
                ->class(['custom-file-input', 'is-invalid' => $errors->has($attributes->get('name'))]) 
                ->merge(['type' => 'file', 'id' => 'file' . Str::studly($attributes->get('nane'))]) }}
            @error($attributes->get('name')) aria-describedby="validation{{ Str::studly($attributes->get('name')) }}Feedback" @enderror
        >
        <label for="file{{ Str::studly($attributes->get('name')) }}" class="custom-file-label">{{ __('Choose file') }}</label>
    </div>
    @error($attributes->get('name'))
        <div class="invalid-feedback" id="validation{{ Str::studly($attributes->get('name')) }}Feedback">
            {{ $message }}
        </div>
    @enderror
</div>