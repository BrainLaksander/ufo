{{--
    Komponen Form Group untuk input fields yang konsisten
    
    Props:
    - $name: Field name
    - $label: Field label
    - $type: text, email, password, number, date, datetime-local, textarea, select
    - $value: Current value (untuk edit)
    - $placeholder: Placeholder text
    - $required: Is required? (default: false)
    - $disabled: Is disabled? (default: false)
    - $error: Show error message? (ambil dari $errors)
    - $hint: Helper text
    - $options: Array untuk select (key => label)
    - $rows: Untuk textarea (default: 4)
--}}

@php
    $fieldError = $errors->get($name);
    $hasError = count($fieldError) > 0;
@endphp

<div class="mb-3">
    @if($label ?? false)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required ?? false)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @switch($type ?? 'text')
        @case('textarea')
            <textarea 
                id="{{ $name }}" 
                name="{{ $name }}" 
                class="form-control {{ $hasError ? 'is-invalid' : '' }}"
                placeholder="{{ $placeholder ?? '' }}"
                {{ ($required ?? false) ? 'required' : '' }}
                {{ ($disabled ?? false) ? 'disabled' : '' }}
                rows="{{ $rows ?? 4 }}">{{ old($name, $value ?? '') }}</textarea>
            @break

        @case('select')
            <select 
                id="{{ $name }}" 
                name="{{ $name }}" 
                class="form-select {{ $hasError ? 'is-invalid' : '' }}"
                {{ ($required ?? false) ? 'required' : '' }}
                {{ ($disabled ?? false) ? 'disabled' : '' }}>
                
                <option value="">{{ 'Pilih ' . ($label ?? 'opsi') }}</option>
                
                @foreach(($options ?? []) as $optValue => $optLabel)
                    <option value="{{ $optValue }}" 
                        @selected(old($name, $value ?? '') == $optValue)>
                        {{ $optLabel }}
                    </option>
                @endforeach
            </select>
            @break

        @default
            <input 
                type="{{ $type ?? 'text' }}" 
                id="{{ $name }}" 
                name="{{ $name }}" 
                class="form-control {{ $hasError ? 'is-invalid' : '' }}"
                value="{{ old($name, $value ?? '') }}"
                placeholder="{{ $placeholder ?? '' }}"
                {{ ($required ?? false) ? 'required' : '' }}
                {{ ($disabled ?? false) ? 'disabled' : '' }}>
    @endswitch

    @if($hint ?? false)
        <small class="form-text text-muted d-block mt-1">{{ $hint }}</small>
    @endif

    @if($hasError)
        <div class="invalid-feedback d-block mt-1">
            @foreach($fieldError as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
</div>
