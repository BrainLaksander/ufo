{{--
    Komponen Action Buttons dengan berbagai style
    
    Props:
    - $link: URL atau route name
    - $method: GET (default), POST, PUT, DELETE
    - $label: Text button
    - $style: primary, secondary, success, danger, warning, info, light (default: primary)
    - $size: sm, md (default), lg
    - $icon: Optional icon class
    - $confirmation: Show confirmation dialog (untuk DELETE)
        Format: ['title' => '...', 'message' => '...']
--}}

@php
    $styleMap = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'light' => 'btn-light',
        'soft-primary' => 'btn-soft-primary',
    ];
    
    $sizeMap = [
        'sm' => 'btn-sm',
        'md' => 'btn',
        'lg' => 'btn-lg',
    ];
    
    $buttonClass = $styleMap[$style ?? 'primary'] ?? 'btn-primary';
    $sizeClass = $sizeMap[$size ?? 'md'] ?? 'btn';
    $needsForm = in_array($method ?? 'GET', ['POST', 'PUT', 'DELETE']) && \Illuminate\Support\Str::startsWith($link ?? '', ['/','http']);
    $formId = 'form-' . md5($link . microtime());
@endphp

@if($needsForm && ($method ?? 'GET') !== 'GET')
    <form action="{{ $link }}" method="POST" id="{{ $formId }}" style="display:inline;">
        @csrf
        @if(($method ?? 'GET') !== 'POST')
            @method(strtoupper($method ?? 'GET'))
        @endif

        <button type="submit" class="btn {{ $buttonClass }} {{ $sizeClass }}"
            @if($confirmation ?? false)
                onclick="return confirm('{{ $confirmation['message'] ?? 'Anda yakin?' }}')"
            @endif>
            @if($icon ?? false)
                <i class="{{ $icon }} me-1"></i>
            @endif
            {{ $label }}
        </button>
    </form>
@else
    <a href="{{ $link }}" class="btn {{ $buttonClass }} {{ $sizeClass }}">
        @if($icon ?? false)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $label }}
    </a>
@endif
