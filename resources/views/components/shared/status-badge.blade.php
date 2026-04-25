{{--
    Komponen Status Badge untuk menampilkan status dengan styling yang sesuai
    
    Props:
    - $status: Status value (draft, published, pending, approved, rejected, active, claimed, etc)
    - $text: Custom text (optional, jika tidak ada akan menggunakan status sebagai text)
--}}

@php
    $statusMap = [
        // Event & Announcement Status
        'draft' => ['badge' => 'bg-secondary', 'icon' => 'bi-pencil', 'text' => 'Draft'],
        'published' => ['badge' => 'bg-success', 'icon' => 'bi-check-circle', 'text' => 'Dipublikasikan'],
        'ongoing' => ['badge' => 'bg-info', 'icon' => 'bi-play-circle', 'text' => 'Sedang Berlangsung'],
        'completed' => ['badge' => 'bg-dark', 'icon' => 'bi-check-all', 'text' => 'Selesai'],
        'cancelled' => ['badge' => 'bg-danger', 'icon' => 'bi-x-circle', 'text' => 'Dibatalkan'],
        
        // Approval Status
        'pending' => ['badge' => 'bg-warning', 'icon' => 'bi-clock', 'text' => 'Menunggu'],
        'approved' => ['badge' => 'bg-success', 'icon' => 'bi-check-lg', 'text' => 'Disetujui'],
        'rejected' => ['badge' => 'bg-danger', 'icon' => 'bi-x-lg', 'text' => 'Ditolak'],
        'under_review' => ['badge' => 'bg-info', 'icon' => 'bi-eye', 'text' => 'Dalam Review'],
        'submitted' => ['badge' => 'bg-warning', 'icon' => 'bi-send', 'text' => 'Diajukan'],
        
        // Lost & Found Status
        'active' => ['badge' => 'bg-warning', 'icon' => 'bi-exclamation-circle', 'text' => 'Aktif'],
        'claimed' => ['badge' => 'bg-success', 'icon' => 'bi-hand-thumbs-up', 'text' => 'Diklaim'],
        'closed' => ['badge' => 'bg-dark', 'icon' => 'bi-lock', 'text' => 'Ditutup'],
        
        // Message Status
        'new' => ['badge' => 'bg-info', 'icon' => 'bi-envelope', 'text' => 'Baru'],
        'read' => ['badge' => 'bg-secondary', 'icon' => 'bi-file-text', 'text' => 'Dibaca'],
        'replied' => ['badge' => 'bg-success', 'icon' => 'bi-arrow-counterclockwise', 'text' => 'Dibalas'],
    ];
    
    $config = $statusMap[$status] ?? ['badge' => 'bg-secondary', 'icon' => 'bi-dash-circle', 'text' => ucfirst($status)];
    $displayText = $text ?? $config['text'];
@endphp

<span class="badge badge-status {{ $config['badge'] }}">
    <i class="bi {{ $config['icon'] }} me-1"></i>{{ $displayText }}
</span>
