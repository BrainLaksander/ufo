@extends('layouts.pengurus')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Dashboard</h1>
    <p>Selamat datang di portal manajemen organisasi UFO</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white;">
            <div class="card-stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="card-stat-number">142</div>
            <div class="card-stat-label">Total Anggota</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #22C55E 0%, #15803D 100%); color: white;">
            <div class="card-stat-icon">
                <i class="bi bi-calendar-event-fill"></i>
            </div>
            <div class="card-stat-number">8</div>
            <div class="card-stat-label">Event Aktif</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #FBBF24 0%, #D97706 100%); color: white;">
            <div class="card-stat-icon">
                <i class="bi bi-megaphone-fill"></i>
            </div>
            <div class="card-stat-number">12</div>
            <div class="card-stat-label">Pengumuman</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #EF4444 0%, #991B1B 100%); color: white;">
            <div class="card-stat-icon">
                <i class="bi bi-search"></i>
            </div>
            <div class="card-stat-number">5</div>
            <div class="card-stat-label">Lost & Found</div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-lightning-charge-fill"></i> Aksi Cepat
                </h5>
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('portal.pengurus.events') }}" class="btn btn-primary-custom w-100 d-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-plus-circle-fill"></i> Buat Event
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('portal.pengurus.announcements') }}" class="btn btn-primary-custom w-100 d-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-plus-circle-fill"></i> Pengumuman
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('portal.pengurus.lostandfound') }}" class="btn btn-primary-custom w-100 d-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-plus-circle-fill"></i> Lost & Found
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <a href="{{ route('portal.pengurus.members') }}" class="btn btn-primary-custom w-100 d-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-people-fill"></i> Kelola Anggota
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Events -->
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-calendar2-check-fill"></i> Event Mendatang
                </h5>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Workshop Python Basics</h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar-date-fill"></i> 15 Mar 2026, 14:00
                            </small>
                        </div>
                        <span class="badge badge-custom badge-primary">Terjadwal</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Seminar Digital Marketing</h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar-date-fill"></i> 20 Mar 2026, 10:00
                            </small>
                        </div>
                        <span class="badge badge-custom badge-success">Confirmed</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Gathering Bulanan UFO</h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar-date-fill"></i> 25 Mar 2026, 15:30
                            </small>
                        </div>
                        <span class="badge badge-custom badge-warning">Planning</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Registrations -->
    <div class="col-md-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-person-plus-fill"></i> Pendaftaran Terbaru
                </h5>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Muhammad Rizki</h6>
                            <small class="text-muted">
                                <i class="bi bi-envelope-fill"></i> m.rizki@email.com
                            </small>
                        </div>
                        <span class="badge badge-custom badge-primary">Pending</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Siti Nurhaliza</h6>
                            <small class="text-muted">
                                <i class="bi bi-envelope-fill"></i> siti.nur@email.com
                            </small>
                        </div>
                        <span class="badge badge-custom badge-success">Approved</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Budi Santoso</h6>
                            <small class="text-muted">
                                <i class="bi bi-envelope-fill"></i> budi.s@email.com
                            </small>
                        </div>
                        <span class="badge badge-custom badge-success">Approved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lost & Found Items -->
    <div class="col-md-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-box2-heart-fill"></i> Lost & Found Terbaru
                </h5>
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Dompet Hitam (HILANG)</h6>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt-fill"></i> Gedung A, Lantai 2
                            </small>
                        </div>
                        <span class="badge badge-custom badge-danger">Open</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Kunci Loker Kuning (DITEMUKAN)</h6>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt-fill"></i> Perpustakaan
                            </small>
                        </div>
                        <span class="badge badge-custom badge-success">Resolved</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Airpods (HILANG)</h6>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt-fill"></i> Ruang Rapat
                            </small>
                        </div>
                        <span class="badge badge-custom badge-warning">Investigating</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Organization Info -->
    <div class="col-md-6 mb-4">
        <div class="card card-dashboard">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <i class="bi bi-info-circle-fill"></i> Info Organisasi
                </h5>
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Nama Organisasi:</strong>
                        <span>UFO - University Forum Organization</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Ketua:</strong>
                        <span>Ahmad Rifki Ramadan</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Email Organisasi:</strong>
                        <span>ufo.org@unklab.ac.id</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Berdiri Sejak:</strong>
                        <span>2020</span>
                    </div>
                </div>
                <a href="{{ route('portal.pengurus.members') }}" class="btn btn-sm btn-primary-custom">
                    <i class="bi bi-pencil-square"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

            <!-- Tugas Aktif -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2 text-warning"></i>Tugas Aktif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(isset($tasks) && is_array($tasks))
                            @foreach($tasks as $task)
                                <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 me-3">
                                        @if(strtolower($task['priority'] ?? '') === 'urgent')
                                            <span class="badge bg-danger">Urgent</span>
                                        @elseif(strtolower($task['priority'] ?? '') === 'normal')
                                            <span class="badge bg-warning">Normal</span>
                                        @else
                                            <span class="badge bg-secondary">Low</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $task['title'] ?? 'Task' }}</h6>
                                        <small class="text-muted">
                                            <i class="bi bi-clock-fill me-1"></i>{{ $task['deadline'] ?? 'No deadline' }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <span class="badge bg-danger">Urgent</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Revisi Proposal Event</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock-fill me-1"></i>2 hari lagi
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0 me-3">
                                    <span class="badge bg-warning">Normal</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Update Profil Organisasi</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock-fill me-1"></i>5 hari lagi
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <span class="badge bg-secondary">Low</span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Upload Dokumentasi Event</h6>
                                    <small class="text-muted">
                                        <i class="bi bi-clock-fill me-1"></i>1 minggu lagi
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection