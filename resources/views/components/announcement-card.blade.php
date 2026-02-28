{{--
    Komponen Announcement Card untuk menampilkan pengumuman
    
    Props:
    - $announcement: Announcement model/object
    - $showActions: Show action buttons? (default: false)
    - $showReply: Show reply button? (default: false)
--}}

<div class="card mb-3 border-start border-3" style="border-left-color: #6A1B9A !important;">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="flex-grow-1">
                <h6 class="card-title mb-1 fw-bold">{{ $announcement->title }}</h6>
                <p class="text-muted small mb-0">
                    <i class="bi bi-calendar3"></i> 
                    {{ $announcement->published_at?->format('d M Y, H:i') ?? 'Belum dijadwalkan' }}
                </p>
            </div>
            @include('components.status-badge', ['status' => $announcement->status])
        </div>

        <p class="card-text small text-secondary mb-3">
            {{ Str::limit($announcement->content, 150) }}
        </p>

        <div class="small text-muted">
            <i class="bi bi-person"></i> {{ $announcement->creator->name ?? 'Tidak diketahui' }}
        </div>

        @if($showActions ?? false)
            <hr class="my-2">
            <div class="d-flex gap-2 justify-content-between">
                <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-sm btn-soft-primary">
                    <i class="bi bi-eye"></i> Baca
                </a>
                @if(auth()->user()->can('update', $announcement))
                    <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
