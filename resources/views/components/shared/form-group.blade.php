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
    $type = $type ?? 'text';
    $currentValue = old($name, $value ?? '');
    $fieldError = $errors->get($name);
    $hasError = !empty($fieldError);
    $inputClass = $type === 'select' ? 'form-select' : 'form-control';
    $inputClass .= $hasError ? ' is-invalid' : '';
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

    @switch($type)
        @case('textarea')
            <textarea 
                id="{{ $name }}" 
                name="{{ $name }}" 
                class="{{ $inputClass }}"
                placeholder="{{ $placeholder ?? '' }}"
                @if($required ?? false) required @endif
                @if($disabled ?? false) disabled @endif
                rows="{{ $rows ?? 4 }}">{{ old($name, $value ?? '') }}</textarea>
            @break

        @case('select')
            <select 
                id="{{ $name }}" 
                name="{{ $name }}" 
                class="{{ $inputClass }}"
                @if($required ?? false) required @endif
                @if($disabled ?? false) disabled @endif>
                
                <option value="" @selected($currentValue === '' || $currentValue === null)>{{ $placeholder ?? ('Pilih ' . ($label ?? 'opsi')) }}</option>
                
                @foreach(($options ?? []) as $optValue => $optLabel)
                    <option value="{{ $optValue }}" @selected((string) $currentValue === (string) $optValue)>
                        {{ $optLabel }}
                    </option>
                @endforeach
            </select>
            @break

        @default
            <input 
                type="{{ $type }}" 
                id="{{ $name }}" 
                name="{{ $name }}" 
                class="{{ $inputClass }}"
                value="{{ $currentValue }}"
                placeholder="{{ $placeholder ?? '' }}"
                @if($required ?? false) required @endif
                @if($disabled ?? false) disabled @endif>
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
