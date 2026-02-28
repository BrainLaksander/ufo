@extends('layouts.pengurus')

@section('title', 'Buat Pengumuman')

@section('content')
<div class="page-header mb-4">
    <h1>Buat Pengumuman Baru</h1>
    <p class="page-subtitle">Buat pengumuman untuk semua anggota organisasi</p>
</div>

<div class="form-org">
    <form>
        <div class="form-group">
            <label>Judul Pengumuman</label>
            <input type="text" placeholder="Contoh: Pengumuman Rapat Rutin Bulan Januari" required>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kategori</label>
                    <select required>
                        <option value="">Pilih Kategori</option>
                        <option value="rapat">Rapat</option>
                        <option value="event">Event</option>
                        <option value="deadline">Deadline</option>
                        <option value="informasi">Informasi Umum</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status Pengumuman</label>
                    <select required>
                        <option value="">Pilih Status</option>
                        <option value="draft">Draft (Belum Publish)</option>
                        <option value="published">Publish Sekarang</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Konten Pengumuman</label>
            <textarea placeholder="Ketik pengumuman Anda di sini..." required style="min-height: 200px;"></textarea>
        </div>

        <div class="form-group">
            <label>Lampiran (Opsional)</label>
            <input type="file">
            <small class="text-muted d-block mt-2">Format: PDF, DOC, PNG, JPG | Max: 10MB</small>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary-org">💾 Simpan & Publish</button>
            <button type="button" class="btn-secondary-org">Draft Saja</button>
            <a href="/portal/pengurus/announcements" class="btn-secondary-org" style="text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection
