@extends('layouts.portal.pengurus')

@section('title', 'Pengajuan & Laporan')

@php
    $pengajuanCount = count($workflowPengajuan ?? []);
    $laporanCount = count($workflowLaporan ?? []);
    $jadwalCount = count($jadwalKegiatan ?? []);

    $statusToPill = function (?string $status): string {
        $value = strtolower((string) $status);

        if (str_contains($value, 'revisi')) {
            return 'revision';
        }

        if (str_contains($value, 'tolak')) {
            return 'rejected';
        }

        if (str_contains($value, 'diajukan') || str_contains($value, 'review')) {
            return 'pending';
        }

        if (str_contains($value, 'setuju')) {
            return 'approved';
        }

        return 'draft';
    };
@endphp

@section('content')
<div class="ufo-kboard-page">
    <section class="ufo-kboard-section">
        <div class="ufo-kboard-item-head">
            <div>
                <h1 class="ufo-kboard-heading">Pengajuan &amp; Laporan Kegiatan</h1>
                <p class="ufo-kboard-lead">Ajukan izin kegiatan dan kirim laporan ke Kemahasiswaan.</p>
            </div>

            <div class="ufo-kboard-item-actions mt-0">
                <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="collapse" data-bs-target="#pengajuanForm">
                    <i class="bi bi-plus-lg"></i>
                    Ajukan Kegiatan
                </button>
                <button class="ufo-kboard-btn gold" type="button" data-bs-toggle="collapse" data-bs-target="#laporanForm">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    Kirim Laporan
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3 mb-0" role="alert">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mt-3 mb-0" role="alert">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger mt-3 mb-0" role="alert">{{ $errors->first() }}</div>
        @endif

        @if(!($hasPengurusContext ?? false))
            <div class="alert alert-warning mt-3 mb-0" role="alert">
                Akun pengurus belum terhubung ke organisasi. Form pengajuan dan laporan dinonaktifkan.
            </div>
        @endif

        <div class="ufo-kboard-row three mt-3">
            <article class="ufo-kboard-stat blue">
                <h3>{{ $pengajuanCount }}</h3>
                <p>Total Pengajuan</p>
            </article>
            <article class="ufo-kboard-stat green">
                <h3>{{ $laporanCount }}</h3>
                <p>Total Laporan</p>
            </article>
            <article class="ufo-kboard-stat purple">
                <h3>{{ $jadwalCount }}</h3>
                <p>Jadwal Terkait</p>
            </article>
        </div>
    </section>

    <section class="ufo-kboard-section collapse" id="pengajuanForm">
        <h3 class="ufo-kboard-item-title mb-3">Form Pengajuan Kegiatan</h3>

        <form method="POST" action="{{ route('portal.pengurus.proposals.store') }}" class="ufo-kboard-row two">
            @csrf
            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Nama Kegiatan</span>
                <input type="text" name="title" class="ufo-kboard-field" placeholder="{{ $proposalTitlePlaceholder ?? 'Proposal Kegiatan Organisasi' }}" required @disabled(!($hasPengurusContext ?? false))>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Jenis Kegiatan</span>
                <select name="type" class="ufo-kboard-select" required @disabled(!($hasPengurusContext ?? false))>
                    <option value="proposal">Proposal</option>
                    <option value="budget">Budget</option>
                    <option value="activity_plan">Activity Plan</option>
                </select>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Penanggung Jawab</span>
                <input type="text" class="ufo-kboard-field" value="{{ $pengurusOrganizationName ?? 'Pengurus Organisasi' }}" disabled>
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Deskripsi Kegiatan</span>
                <textarea name="description" class="ufo-kboard-textarea" placeholder="{{ $proposalDescriptionPlaceholder ?? 'Jelaskan detail kegiatan yang akan diajukan.' }}" required @disabled(!($hasPengurusContext ?? false))></textarea>
            </label>

            <div class="ufo-kboard-span-full">
                <label class="ufo-kboard-item-meta mb-2 d-block">Lampiran Proposal (PDF)</label>
                <div class="ufo-pg-upload-box">
                    <i class="bi bi-cloud-upload"></i>
                    <p class="mb-0">Klik untuk upload proposal</p>
                    <small>PDF (maks. 10MB)</small>
                </div>
            </div>

            <div class="ufo-kboard-item-actions mt-2 ufo-kboard-span-full">
                <button type="submit" class="ufo-kboard-btn primary" @disabled(!($hasPengurusContext ?? false))>
                    Ajukan Kegiatan ke Kemahasiswaan
                </button>
                <button type="button" class="ufo-kboard-btn ghost" data-bs-toggle="collapse" data-bs-target="#pengajuanForm">
                    Batal
                </button>
            </div>
        </form>

        <div class="alert alert-info mt-3 mb-0" role="alert">
            Pastikan informasi kegiatan lengkap sebelum diajukan agar proses review berjalan lebih cepat.
        </div>
    </section>

    <section class="ufo-kboard-section collapse" id="laporanForm">
        <h3 class="ufo-kboard-item-title mb-3">Form Laporan Kegiatan</h3>

        <form method="POST" action="{{ route('portal.pengurus.reports.store') }}" class="ufo-kboard-row two">
            @csrf
            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Nama Kegiatan</span>
                <input type="text" name="title" class="ufo-kboard-field" placeholder="{{ $reportTitlePlaceholder ?? 'Laporan Kegiatan Organisasi' }}" required @disabled(!($hasPengurusContext ?? false))>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Tipe Laporan</span>
                <select name="report_type" class="ufo-kboard-select" required @disabled(!($hasPengurusContext ?? false))>
                    <option value="activity">Activity</option>
                    <option value="financial">Financial</option>
                    <option value="semester">Semester</option>
                </select>
            </label>

            <label>
                <span class="ufo-kboard-item-meta">Jumlah Peserta (Real)</span>
                <input type="number" min="0" name="participants" class="ufo-kboard-field" value="0" required @disabled(!($hasPengurusContext ?? false))>
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Event Terkait (Opsional)</span>
                <select name="event_id" class="ufo-kboard-select" @disabled(!($hasPengurusContext ?? false))>
                    <option value="">Tanpa event</option>
                    @foreach(($eventOptions ?? []) as $event)
                        <option value="{{ $event['id'] }}">{{ $event['name'] }}</option>
                    @endforeach
                </select>
            </label>

            <label class="ufo-kboard-span-full">
                <span class="ufo-kboard-item-meta">Ringkasan Laporan</span>
                <textarea name="content" class="ufo-kboard-textarea" placeholder="{{ $reportContentPlaceholder ?? 'Ceritakan hasil kegiatan, evaluasi, dan dampak program.' }}" required @disabled(!($hasPengurusContext ?? false))></textarea>
            </label>

            <div class="ufo-kboard-span-full">
                <label class="ufo-kboard-item-meta mb-2 d-block">Dokumentasi / Lampiran</label>
                <div class="ufo-pg-upload-box">
                    <i class="bi bi-images"></i>
                    <p class="mb-0">Upload dokumentasi kegiatan</p>
                    <small>JPG, PNG, PDF</small>
                </div>
            </div>

            <div class="ufo-kboard-item-actions mt-2 ufo-kboard-span-full">
                <button type="submit" class="ufo-kboard-btn gold" @disabled(!($hasPengurusContext ?? false))>
                    Kirim Laporan ke Kemahasiswaan
                </button>
                <button type="button" class="ufo-kboard-btn ghost" data-bs-toggle="collapse" data-bs-target="#laporanForm">
                    Batal
                </button>
            </div>
        </form>

        <div class="alert alert-warning mt-3 mb-0" role="alert">
            Laporan dikirim maksimal 7 hari setelah kegiatan selesai.
        </div>
    </section>

    <section class="ufo-kboard-section">
        <ul class="nav nav-pills mb-3" id="proposalTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pengajuan-tab" data-bs-toggle="pill" data-bs-target="#pengajuan-pane" type="button" role="tab" aria-controls="pengajuan-pane" aria-selected="true">
                    Pengajuan Kegiatan ({{ $pengajuanCount }})
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="laporan-tab" data-bs-toggle="pill" data-bs-target="#laporan-pane" type="button" role="tab" aria-controls="laporan-pane" aria-selected="false">
                    Laporan Kegiatan ({{ $laporanCount }})
                </button>
            </li>
        </ul>

        <div class="tab-content" id="proposalTabsContent">
            <div class="tab-pane fade show active" id="pengajuan-pane" role="tabpanel" aria-labelledby="pengajuan-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @forelse(($workflowPengajuan ?? []) as $item)
                        @php
                            $pill = $statusToPill($item['status'] ?? '');
                            $status = (string) ($item['status'] ?? '');
                        @endphp

                        <article class="ufo-kboard-item">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['judul'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['organisasi'] }} • {{ \Carbon\Carbon::parse($item['tanggal_kegiatan'])->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $pill }}">{{ $status }}</span>
                            </div>

                            @if(!empty($item['catatan_departemen']))
                                <div class="ufo-pg-review-note">
                                    <strong>Catatan Kemahasiswaan:</strong>
                                    <span>{{ $item['catatan_departemen'] }}</span>
                                </div>
                            @endif

                            <div class="ufo-kboard-item-actions">
                                <button class="ufo-kboard-btn ghost" type="button">Lihat Detail</button>

                                @if(in_array($status, ['Draft', 'Revisi'], true))
                                    <form method="POST" action="{{ route('portal.pengurus.proposals.submit', ['id' => $item['id']]) }}">
                                        @csrf
                                        <button type="submit" class="ufo-kboard-btn gold">Kirim</button>
                                    </form>
                                @endif

                                @if($status === 'Disetujui')
                                    <button class="ufo-kboard-btn primary" type="button" data-bs-toggle="collapse" data-bs-target="#laporanForm">Kirim Laporan</button>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="ufo-kboard-item-meta">Belum ada data pengajuan kegiatan.</p>
                    @endforelse
                </div>
            </div>

            <div class="tab-pane fade" id="laporan-pane" role="tabpanel" aria-labelledby="laporan-tab" tabindex="0">
                <div class="ufo-kboard-list">
                    @forelse(($workflowLaporan ?? []) as $item)
                        @php
                            $pill = $statusToPill($item['status'] ?? '');
                            $status = (string) ($item['status'] ?? '');
                        @endphp

                        <article class="ufo-kboard-item">
                            <div class="ufo-kboard-item-head">
                                <div>
                                    <h3 class="ufo-kboard-item-title">{{ $item['judul'] }}</h3>
                                    <p class="ufo-kboard-item-meta">{{ $item['organisasi'] }} • {{ \Carbon\Carbon::parse($item['tanggal_laporan'])->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="ufo-kboard-pill {{ $pill }}">{{ $status }}</span>
                            </div>

                            @if(!empty($item['catatan_departemen']))
                                <div class="ufo-pg-review-note">
                                    <strong>Catatan Kemahasiswaan:</strong>
                                    <span>{{ $item['catatan_departemen'] }}</span>
                                </div>
                            @endif

                            <div class="ufo-kboard-item-actions">
                                <button class="ufo-kboard-btn ghost" type="button">Lihat Laporan</button>

                                @if(in_array($status, ['Draft', 'Revisi'], true))
                                    <form method="POST" action="{{ route('portal.pengurus.reports.submit', ['id' => $item['id']]) }}">
                                        @csrf
                                        <button type="submit" class="ufo-kboard-btn gold">Kirim</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @empty
                        <p class="ufo-kboard-item-meta">Belum ada data laporan kegiatan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <div class="ufo-kboard-row two">
        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Jadwal Kegiatan Organisasi</h3>
                <span class="ufo-kboard-pill approved">{{ count($jadwalKegiatan ?? []) }} Jadwal</span>
            </div>

            <div class="ufo-kboard-table-wrap">
                <table class="ufo-kboard-table">
                    <thead>
                        <tr>
                            <th>Kegiatan</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($jadwalKegiatan ?? []) as $jadwal)
                            <tr>
                                <td>
                                    <strong>{{ $jadwal['judul'] }}</strong>
                                    <div class="ufo-kboard-item-meta">{{ $jadwal['organisasi'] }}</div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($jadwal['tanggal'])->translatedFormat('d M Y') }}</td>
                                <td>{{ $jadwal['lokasi'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">Belum ada jadwal kegiatan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="ufo-kboard-section">
            <div class="ufo-kboard-item-head">
                <h3 class="ufo-kboard-item-title">Kontak Pengurus UKM</h3>
                <span class="ufo-kboard-pill draft">{{ count($kontakPengurus ?? []) }} Kontak</span>
            </div>

            <div class="ufo-kboard-list">
                @forelse(($kontakPengurus ?? []) as $kontak)
                    <article class="ufo-kboard-item">
                        <div class="ufo-kboard-item-head">
                            <div>
                                <h4 class="ufo-kboard-item-title">{{ $kontak['nama'] }}</h4>
                                <p class="ufo-kboard-item-meta">{{ $kontak['jabatan'] }} • {{ $kontak['organisasi'] }}</p>
                            </div>
                            <span class="ufo-kboard-pill approved">Aktif</span>
                        </div>

                        <p class="ufo-kboard-item-text"><i class="bi bi-telephone"></i> {{ $kontak['kontak'] }}</p>
                        <p class="ufo-kboard-item-text"><i class="bi bi-envelope"></i> {{ $kontak['email'] }}</p>
                    </article>
                @empty
                    <p class="ufo-kboard-item-meta">Belum ada kontak pengurus.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
