{{--
    Komponen Stat Card untuk menampilkan statistik dengan icon dan nilai
    
    Props:
    - $icon: Icon class (bi bi-icon)
    - $label: Label text
    - $value: Nilai/angka
    - $bgColor: Background color (primary, secondary, success, danger, warning, info)
    - $link: Optional link untuk clickable card
--}}

@php
    $bgColorMap = [
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'success' => 'bg-success',
        'danger' => 'bg-danger',
        'warning' => 'bg-warning',
        'info' => 'bg-info',
    ];
    
    $bgClass = $bgColorMap[$bgColor ?? 'primary'] ?? 'bg-primary';
@endphp

<div class="card card-stat">
    <div class="card-body d-flex align-items-center">
        <div class="flex-shrink-0 me-3">
            <div class="p-3 rounded-3 {{ $bgClass }} text-white" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                <i class="{{ $icon }}"></i>
            </div>
        </div>
        <div class="flex-grow-1">
            <p class="text-muted mb-1" style="font-size: 0.85rem;">{{ $label }}</p>
            <h5 class="mb-0 fw-bold">{{ $value }}</h5>
        </div>
    </div>
    
    @if($link ?? false)
        <a href="{{ $link }}" class="stretched-link"></a>
    @endif
</div>
