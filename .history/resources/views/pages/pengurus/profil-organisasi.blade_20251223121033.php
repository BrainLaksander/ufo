@extends('layouts.app')

@section('content')
  <div class="profil-wrap">
    <div class="profil-grid">
      <div class="left">
        <div class="card">
          <div class="card-row">
            <div class="logo-placeholder">Logo</div>
            <div class="banner-placeholder">Banner</div>
          </div>

          <div class="form-row">
            <label>Kategori</label>
            <select id="org-kategori" class="form-input">
              <option>Himpunan</option>
              <option>UKM</option>
              <option>Komunitas</option>
            </select>
          </div>

          <div class="form-row">
            <label>Total Anggota Aktif</label>
            <div id="org-total" class="stat-large">85</div>
          </div>

          <div class="form-row">
            <label>Visi & Misi</label>
            <div class="editable-block" data-editable>
              <div class="view-mode">
                <p id="visi-misi">Visi: Menjadi organisasi contoh. Misi: Melakukan kegiatan edukasi.</p>
                <button class="btn-sm" data-toggle-edit>Edit</button>
              </div>
              <div class="edit-mode hidden">
                <textarea id="visi-misi-input" class="form-input">Visi: Menjadi organisasi contoh. Misi: Melakukan kegiatan edukasi.</textarea>
                <div class="mt-2">
                  <button class="btn-sm" data-save>Edit Simpan</button>
                  <button class="btn-sm btn-secondary" data-cancel>Edit Batal</button>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="right">
        <div class="card">
          <h3>Program Kegiatan</h3>
          <ul id="program-list" class="program-list">
            <li data-id="1">
              <div class="prog-row">
                <div class="prog-title">Workshop Coding</div>
                <div class="prog-actions">
                  <button class="btn-sm" data-toggle-expand>Detail</button>
                  <button class="btn-sm btn-danger" data-remove>Hapus</button>
                </div>
              </div>
              <div class="prog-detail hidden">Detail lengkap workshop coding.</div>
            </li>
          </ul>
          <div class="mt-3">
            <input id="new-program" class="form-input" placeholder="Nama program baru">
            <button id="add-program" class="btn-primary mt-2">Tambah Program</button>
          </div>
        </div>

        <div class="card mt-4">
          <h3>Struktur Kepengurusan</h3>
          <p class="text-muted">(Static placeholder)</p>
        </div>

        <div class="card mt-4">
          <h3>Sosial Media</h3>
          <ul class="social-list">
            <li>Instagram: @ufo_example</li>
            <li>Twitter: @ufo_example</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
@endsection
