{{--
    Komponen Modal Dialog untuk konfirmasi atau menampilkan konten
    
    Props:
    - $id: Modal ID (unique)
    - $title: Modal title
    - $size: sm, md (default), lg, xl
    - $closeButton: Show close button? (default: true)
    - $footer: Show default footer? (default: true)
    - $submitButtonLabel: Label tombol submit (default: 'Simpan')
    - $submitButtonStyle: Warna tombol submit (default: 'primary')
    - $cancelButtonLabel: Label tombol batal (default: 'Batal')
    - Content: Slot content untuk body modal
--}}

@php
    $sizeMap = [
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
    ];
    
    $sizeClass = $sizeMap[$size ?? 'md'] ?? '';
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label"
    aria-hidden="true">
    <div class="modal-dialog {{ $sizeClass }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                @if($closeButton ?? true)
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @endif
            </div>

            <div class="modal-body">
                {{ $slot }}
            </div>

            @if($footer ?? true)
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ $cancelButtonLabel ?? 'Batal' }}
                    </button>
                    <button type="button" class="btn btn-{{ $submitButtonStyle ?? 'primary' }}"
                        id="{{ $id }}-submit">
                        {{ $submitButtonLabel ?? 'Simpan' }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
