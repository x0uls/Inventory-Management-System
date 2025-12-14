<div class="form-group">
    <label for="{{ $name }}" class="form-label {{ $required ?? false ? 'required' : '' }}">
        {{ $label }}
    </label>
    <input 
        type="{{ $type ?? 'text' }}"
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-input"
        placeholder="{{ $placeholder ?? '' }}"
        value="{{ $value ?? old($name) }}"
        {{ ($required ?? false) ? 'required' : '' }}
    >
    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
