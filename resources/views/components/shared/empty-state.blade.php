{{--
    Komponen Empty State untuk menampilkan pesan ketika tidak ada data
    
    Props:
    - $icon: Icon class (default: 'bi-inbox')
    - $title: Judul
    - $message: Pesan keterangan
    - $actionLabel: Label tombol aksi (optional)
    - $actionLink: Link untuk tombol aksi (optional)
--}}

<div class="text-center py-5">
    <div class="mb-3">
        <i class="bi {{ $icon ?? 'bi-inbox' }} ufo-empty-icon"></i>
    </div>
    
    @if($title ?? false)
        <h5 class="text-muted fw-bold mb-2">{{ $title }}</h5>
    @endif
    
    @if($message ?? false)
        <p class="text-muted small">{{ $message }}</p>
    @endif
    
    @if(($actionLabel ?? false) && ($actionLink ?? false))
        <a href="{{ $actionLink }}" class="btn btn-primary btn-sm mt-3">
            <i class="bi bi-plus-lg"></i> {{ $actionLabel }}
        </a>
    @endif
</div>
