@extends('layouts.pengurus')

@section('title', 'Buat Event')

@section('content')
<div class="page-header mb-4">
    <h1>Buat Event Baru</h1>
    <p class="page-subtitle">Isi form di bawah untuk membuat event organisasi</p>
</div>

<div class="form-org">
    <form>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Nama Event</label>
                    <input type="text" placeholder="Contoh: Workshop Web Development" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tanggal & Waktu</label>
                    <input type="datetime-local" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Lokasi</label>
                    <input type="text" placeholder="Contoh: Aula Gedung A" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kuota Peserta</label>
                    <input type="number" placeholder="Contoh: 100" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Event</label>
            <textarea placeholder="Jelaskan tujuan, agenda, dan keuntungan mengikuti event ini..." required></textarea>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Upload Banner Event</label>
                    <input type="file" accept="image/*">
                    <small class="text-muted d-block mt-2">Format: JPG, PNG | Max: 5MB</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Status Event</label>
                    <select required>
                        <option value="">Pilih Status</option>
                        <option value="draft">Draft (Belum Publish)</option>
                        <option value="open">Open (Buka Pendaftaran)</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-top: 30px;">
            <button type="submit" class="btn-primary-org">💾 Simpan & Publish</button>
            <button type="button" class="btn-secondary-org">Draft Saja</button>
            <a href="/portal/pengurus/events" class="btn-secondary-org" style="text-decoration: none;">Batal</a>
        </div>
    </form>
</div>
@endsection
