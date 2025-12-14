<div class="form-group">
    <label for="{{ $name }}" class="form-label {{ $required ?? false ? 'required' : '' }}">
        {{ $label }}
    </label>
    <select 
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-select"
        {{ ($required ?? false) ? 'required' : '' }}
    >
        @if(isset($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>
    @error($name)
        <div class="form-error">{{ $message }}</div>
    @enderror
</div>
