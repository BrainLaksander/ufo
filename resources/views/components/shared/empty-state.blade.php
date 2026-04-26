{{--
    Komponen Empty State untuk menampilkan pesan ketika tidak ada data

    Props:
    - $icon: Icon class (default: 'bi-inbox')
    - $title: Judul
    - $message: Pesan keterangan
    - $actionLabel: Label tombol aksi (optional)
    - $actionLink: Link untuk tombol aksi (optional)
    - $actionStyle: Style tombol aksi (default: 'primary')
    - $actionSize: Ukuran tombol aksi (default: 'sm')
--}}

<div class="text-center py-5">
    <div class="mb-3">
        <i class="bi {{ $icon ?? 'bi-inbox' }} ufo-empty-icon"></i>
    </div>

    @if($title ?? false)
        <h5 class="text-muted fw-bold mb-2">{{ $title }}</h5>
    @endif

    @if($message ?? false)
        <p class="text-muted small mb-0">{{ $message }}</p>
    @endif

    @if(($actionLabel ?? false) && ($actionLink ?? false))
        <div class="mt-3">
            <x-shared.action-button
                :link="$actionLink"
                :label="$actionLabel"
                :style="$actionStyle ?? 'primary'"
                :size="$actionSize ?? 'sm'"
                icon="bi bi-plus-lg"
            />
        </div>
    @endif
</div>
