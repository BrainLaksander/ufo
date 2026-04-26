{{--
    Komponen Alert untuk menampilkan pesan dengan styling
    
    Props:
    - $type: success, info, warning, danger (default: info)
    - $title: Judul alert
    - $message: Pesan/content
    - $dismissible: Bisa ditutup? (default: true)
    - $icon: Disable icon? (default: false)
--}}

@php
    $typeMap = [
        'success' => ['class' => 'alert-success', 'icon' => 'bi-check-circle-fill'],
        'info' => ['class' => 'alert-info', 'icon' => 'bi-info-circle-fill'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'bi-exclamation-triangle-fill'],
        'danger' => ['class' => 'alert-danger', 'icon' => 'bi-exclamation-circle-fill'],
    ];
    
    $config = $typeMap[$type ?? 'info'] ?? $typeMap['info'];
@endphp

<div class="alert {{ $config['class'] }} {{ ($dismissible ?? true) ? 'alert-dismissible fade show' : '' }} mt-3"
    role="alert">
    @if(!($icon ?? false))
        <i class="bi {{ $config['icon'] }} me-2"></i>
    @endif
    
    @if($title ?? false)
        <strong>{{ $title }}</strong>
    @endif
    
    @if($title ?? false)
        <br>
    @endif
    
    {{ $message ?? $slot }}
    
    @if($dismissible ?? true)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
