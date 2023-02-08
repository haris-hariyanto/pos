{{-- Data Properties ['name', 'index', 'label'] --}}

@props(['name', 'index', 'label'])

<div class="form-group">
    <label for="field{{ $name }}{{ $index }}">{{ $label }}</label>
    <input
        {{ $attributes
            ->class(['form-control', 'is-invalid' => $errors->has($name . '.' . $index) ])
            ->merge(['type' => 'text', 'name' => $name . '[' . $index . ']', 'autocomplete' => 'off', 'id' => 'field' . $name . $index]) }}
        @error($name . '.' . $index) aria-describedby="validation{{ $name }}{{ $index }}Feedback" @enderror
    >
    @error($name . '.' . $index)
        <div class="invalid-feedback" id="validation{{ $name }}{{ $index }}Feedback">
            {{ $message }}
        </div>
    @enderror
</div>