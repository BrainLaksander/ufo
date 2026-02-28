@extends('layouts.dashboard-master')

@section('title', 'Review Proposal - ' . $proposal->title)

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="section-title">
                <i class="bi bi-file-earmark-text me-2"></i>
                Review {{ $proposal->title }}
            </h2>
        </div>
        <div class="col-md-4 text-end">
            @include('components.status-badge', ['status' => $proposal->status])
        </div>
    </div>

    <div class="row">
        {{-- PROPOSAL DETAIL --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-info-circle me-2"></i>Detail Proposal</h5>
                </div>
                <div class="card-body">
                    {{-- Basic Info --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold mb-2">Judul</h6>
                        <p>{{ $proposal->title }}</p>

                        <h6 class="fw-bold mb-2">Organisasi</h6>
                        <p>{{ $proposal->organization->name }}</p>

                        <h6 class="fw-bold mb-2">Tipe Pengajuan</h6>
                        <p>
                            <span class="badge" style="background-color: #FFC107; color: black;">
                                {{ ucfirst($proposal->type) }}
                            </span>
                        </p>
                    </div>

                    {{-- Description --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold mb-2">Deskripsi</h6>
                        <p class="text-secondary">{{ $proposal->description }}</p>
                    </div>

                    {{-- Budget & Timeline --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-2">Anggaran</h6>
                                <p class="text-secondary">
                                    @if($proposal->budget)
                                        Rp {{ number_format($proposal->budget, 0, ',', '.') }}
                                    @else
                                        <em>Tidak ada</em>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-2">Timeline</h6>
                                <p class="text-secondary">
                                    @if($proposal->timeline)
                                        {{ $proposal->timeline->format('d F Y') }}
                                    @else
                                        <em>Tidak ada</em>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submitted By --}}
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold mb-2">Diajukan Oleh</h6>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $proposal->submitter->getAvatarUrl() }}" alt="Avatar" 
                                class="rounded-circle" width="40" height="40">
                            <div>
                                <p class="mb-0"><strong>{{ $proposal->submitter->name }}</strong></p>
                                <small class="text-muted">{{ $proposal->submitter->email }}</small>
                            </div>
                        </div>
                        <p class="text-muted small mt-2">
                            {{ $proposal->created_at->format('d F Y H:i') }}
                        </p>
                    </div>

                    {{-- Attachment --}}
                    @if($proposal->attachment)
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Lampiran</h6>
                            <a href="{{ Storage::url($proposal->attachment) }}" 
                                class="btn btn-sm btn-secondary" target="_blank">
                                <i class="bi bi-download me-1"></i>
                                Download Dokumen
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Previous Reviews (jika ada) --}}
            @if($proposal->status !== 'draft')
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="bi bi-clock-history me-2"></i>Status Review</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline-wrapper">
                            {{-- Submitted --}}
                            <div class="timeline-item mb-3 pb-3 border-bottom">
                                <div class="d-flex gap-3">
                                    <div class="flex-shrink-0">
                                        <i class="bi bi-send-fill" style="font-size: 1.5rem; color: #6A1B9A;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="fw-bold mb-0">Diajukan</p>
                                        <small class="text-muted">
                                            {{ $proposal->submitted_at?->format('d F Y H:i') ?? '-' }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- Under Review --}}
                            @if(in_array($proposal->status, ['under_review', 'approved', 'rejected']))
                                <div class="timeline-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <i class="bi bi-eye-fill" style="font-size: 1.5rem; color: #0DCAF0;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fw-bold mb-0">Dalam Review</p>
                                            <small class="text-muted">Oleh admin sistem</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Approved/Rejected --}}
                            @if($proposal->status === 'approved')
                                <div class="timeline-item">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <i class="bi bi-check-circle-fill" style="font-size: 1.5rem; color: #198754;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fw-bold mb-0">Disetujui</p>
                                            <small class="text-muted">
                                                {{ $proposal->approved_at?->format('d F Y H:i') ?? '-' }}
                                            </small>
                                            @if($proposal->approval_notes)
                                                <p class="text-secondary small mt-2">
                                                    <strong>Catatan Persetujuan:</strong><br>{{ $proposal->approval_notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @elseif($proposal->status === 'rejected')
                                <div class="timeline-item">
                                    <div class="d-flex gap-3">
                                        <div class="flex-shrink-0">
                                            <i class="bi bi-x-circle-fill" style="font-size: 1.5rem; color: #DC3545;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="fw-bold mb-0">Ditolak</p>
                                            <small class="text-muted">
                                                {{ $proposal->rejected_at?->format('d F Y H:i') ?? '-' }}
                                            </small>
                                            @if($proposal->rejection_reason)
                                                <p class="text-danger small mt-2">
                                                    <strong>Alasan Penolakan:</strong><br>{{ $proposal->rejection_reason }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ADMIN ACTION PANEL --}}
        @if(auth()->user()->isAdmin() && in_array($proposal->status, ['submitted', 'under_review']))
            <div class="col-lg-4">
                <div class="card border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-check-square me-2"></i>Aksi Review</h5>
                    </div>
                    <div class="card-body">
                        {{-- Approve Form --}}
                        <form action="{{ route('proposals.approve', $proposal) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <label for="approval_notes" class="form-label">Catatan Persetujuan (Opsional)</label>
                                <textarea id="approval_notes" name="approval_notes" class="form-control" 
                                    rows="4" placeholder="Berikan catatan atau feedback..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="bi bi-check-lg me-1"></i> Setujui Pengajuan
                            </button>
                        </form>

                        <hr>

                        {{-- Reject Form --}}
                        <form action="{{ route('proposals.reject', $proposal) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea id="rejection_reason" name="rejection_reason" class="form-control" 
                                    rows="4" placeholder="Jelaskan mengapa pengajuan ditolak..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100" 
                                onclick="return confirm('Yakin menolak pengajuan ini?')">
                                <i class="bi bi-x-lg me-1"></i> Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card mt-3 bg-light">
                    <div class="card-body small">
                        <p class="mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Panduan:</strong> Review detail proposal di atas, kemudian 
                            buat keputusan approval atau rejection dengan memberikan feedback yang jelas.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
