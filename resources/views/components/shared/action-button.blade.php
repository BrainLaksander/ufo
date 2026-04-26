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

    $method = strtoupper($method ?? 'GET');
    $link = $link ?? '#';
    $resolvedLink = \Illuminate\Support\Str::startsWith($link, ['/','http']) ? $link : route($link);
    $buttonClass = $styleMap[$style ?? 'primary'] ?? 'btn-primary';
    $sizeClass = $sizeMap[$size ?? 'md'] ?? 'btn';
    $needsForm = in_array($method, ['POST', 'PUT', 'DELETE'], true);
@endphp

@if($needsForm)
    <form action="{{ $resolvedLink }}" method="POST" style="display:inline;">
        @csrf
        @if($method !== 'POST')
            @method($method)
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
    <a href="{{ $resolvedLink }}" class="btn {{ $buttonClass }} {{ $sizeClass }}">
        @if($icon ?? false)
            <i class="{{ $icon }} me-1"></i>
        @endif
        {{ $label }}
    </a>
@endif
