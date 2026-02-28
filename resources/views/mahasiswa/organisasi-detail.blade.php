@extends('layouts.mahasiswa')

@section('title', $organisasi['nama'] . ' - Detail Organisasi')

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('mahasiswa.organisasi.index') }}" class="btn btn-outline-secondary btn-sm">
            ← Kembali ke Daftar
        </a>
    </div>

    <!-- Header with Banner -->
    <div class="bg-gradient-to-r {{ $organisasi['banner_gradient'] }} text-white rounded-lg p-5 mb-5">
        <h1 class="display-4 fw-bold mb-2">{{ $organisasi['emoji'] }} {{ $organisasi['nama'] }}</h1>
        <p class="lead mb-0">{{ $organisasi['tagline'] }}</p>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Deskripsi -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">📝 Tentang Organisasi</h5>
                    <p class="card-text">{{ $organisasi['deskripsi'] }}</p>
                </div>
            </div>

            <!-- Visi & Misi -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">🎯 Visi & Misi</h5>
                    <div class="row">
                        @foreach($organisasi['visiMisi'] as $item)
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-bold text-dark">{{ $item['type'] === 'visi' ? '🔭 Visi' : '🎪 Misi' }}</h6>
                            <p class="text-muted mb-0">{{ $item['value'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Budaya Organisasi -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">🌟 Budaya Organisasi</h5>
                    <p class="card-text">{{ $organisasi['budaya'] }}</p>
                </div>
            </div>

            <!-- Program -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">📌 Program & Kegiatan</h5>
                    <div class="list-group list-group-flush">
                        @forelse($organisasi['programs'] as $program)
                        <div class="list-group-item">
                            <h6 class="mb-2 fw-bold">{{ $program['name'] }}</h6>
                            <p class="text-muted small mb-0">{{ $program['description'] }}</p>
                        </div>
                        @empty
                        <p class="text-muted">Belum ada program</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Acara Mendatang -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">📅 Acara Mendatang</h5>
                    <div class="list-group list-group-flush">
                        @forelse($organisasi['events'] as $event)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 fw-bold">{{ $event['name'] }}</h6>
                                    <p class="text-muted small mb-0">{{ $event['date'] }}</p>
                                </div>
                                <span class="badge bg-success">{{ $event['status'] }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted">Belum ada acara mendatang</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Struktur Organisasi -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title text-primary">👥 Struktur Organisasi</h5>
                    <div class="row">
                        @forelse($organisasi['struktur'] as $posisi)
                        <div class="col-md-6 mb-3">
                            <div class="bg-light p-3 rounded">
                                <h6 class="fw-bold text-primary">{{ $posisi['posisi'] }}</h6>
                                <p class="text-muted small mb-0">{{ $posisi['nama'] }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-muted">Struktur belum tersedia</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <!-- Info Card -->
            <div class="card position-sticky" style="top: 20px;">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-3">📋 Informasi</h5>
                    
                    <div class="mb-4">
                        <label class="text-muted small fw-bold">KATEGORI</label>
                        <p class="mb-0">
                            <span class="badge bg-primary">{{ $organisasi['kategori'] }}</span>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold">ANGGOTA</label>
                        <p class="mb-0 fw-bold text-dark">👥 {{ $organisasi['members'] }} Anggota</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold">KONTAK</label>
                        <p class="mb-0">{{ $organisasi['contact'] }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="text-muted small fw-bold">TELEPON</label>
                        <p class="mb-0">
                            <a href="tel:{{ $organisasi['phone'] }}" class="text-decoration-none">
                                {{ $organisasi['phone'] }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small fw-bold">STATUS PENDAFTARAN</label>
                        <p class="mb-0">
                            @if($organisasi['registrationOpen'])
                                <span class="badge bg-success">🔓 Buka</span>
                            @else
                                <span class="badge bg-danger">🔒 Tutup</span>
                            @endif
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        @if($organisasi['registrationOpen'])
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#registrationModal">
                            📝 Daftar Sekarang
                        </button>
                        @else
                        <button class="btn btn-secondary btn-lg" disabled>
                            🔒 Pendaftaran Tutup
                        </button>
                        @endif
                        
                        <a href="https://wa.me/62{{ substr($organisasi['phone'], 1) }}" class="btn btn-outline-success" target="_blank">
                            💬 WhatsApp
                        </a>
                        <a href="mailto:{{ $organisasi['contact'] }}" class="btn btn-outline-primary">
                            ✉️ Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pendaftaran {{ $organisasi['nama'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="registrationForm">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telepon</label>
                        <input type="tel" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivasi Bergabung</label>
                        <textarea class="form-control" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="submitRegistration()">Kirim Pendaftaran</button>
            </div>
        </div>
    </div>
</div>

<script>
    function submitRegistration() {
        alert('Pendaftaran berhasil dikirim! Tim organisasi akan menghubungi Anda segera.');
        document.getElementById('registrationForm').reset();
        bootstrap.Modal.getInstance(document.getElementById('registrationModal')).hide();
    }
</script>
@endsection
