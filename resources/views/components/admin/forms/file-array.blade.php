{{-- Data Properties ['name', 'index', 'label'] --}}

@props(['name', 'index', 'label'])

<div class="form-group">
    <label for="file{{ $name }}{{ $index }}">{{ $label }}</label>
    <div class="custom-file">
        <input
            {{ $attributes
                ->class(['custom-file-input', 'is-invalid' => $errors->has($name . '.' . $index)]) 
                ->merge(['type' => 'file', 'name' => $name . '[' . $index . ']', 'id' => 'file' . $name . $index]) }}
            @error($name . '.' . $index) aria-describedby="validation{{ $name }}{{ $index }}Feedback" @enderror
        >
        <label for="file{{ $name }}{{ $index }}" class="custom-file-label">{{ __('Choose file') }}</label>
        @error($name . '.' . $index)
            <div class="invalid-feedback" id="validation{{ $name }}{{ $index }}Feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>