<div class="form-group">
    <label for="{{ $name }}" class="form-label {{ $required ?? false ? 'required' : '' }}">
        {{ $label }}
    </label>
    <textarea 
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-textarea"
        placeholder="{{ $placeholder ?? '' }}"
        {{ ($required ?? false) ? 'required' : '' }}
    >{{ $value ?? old($name) }}</textarea>
    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
