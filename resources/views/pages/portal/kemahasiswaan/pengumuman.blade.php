@extends('layouts.kemahasiswaan')

@section('title', 'Manajemen Pengumuman & Distribusi Email - Kemahasiswaan')

@section('content')

<div class="container-fluid py-5">

  <!-- PAGE HEADER -->
  <div class="mb-5">
    <h1 class="display-6 fw-bold" style="color: #5f3a74;">Manajemen Pengumuman &amp; Distribusi Email</h1>
    <p class="text-muted">Kelola pengumuman, jadwalkan publikasi, dan distribusikan melalui email resmi UNKLAB.</p>
  </div>

  <!-- ===== 1. SECTION STATISTIK ===== -->
  <div class="row g-4 mb-5">
    <!-- Card 1: Total Pengumuman -->
    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #f8f9fa;">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-3">Total Pengumuman</small>
          <h2 class="fw-bold" style="color: #5f3a74; font-size: 2.5rem;">6</h2>
        </div>
      </div>
    </div>

    <!-- Card 2: Terpublikasi -->
    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #f8f9fa;">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-3">Terpublikasi</small>
          <h2 class="fw-bold" style="color: #2E7D32; font-size: 2.5rem;">3</h2>
        </div>
      </div>
    </div>

    <!-- Card 3: Terjadwal -->
    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #f8f9fa;">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-3">Terjadwal</small>
          <h2 class="fw-bold" style="color: #1976D2; font-size: 2.5rem;">2</h2>
        </div>
      </div>
    </div>

    <!-- Card 4: Draft -->
    <div class="col-md-3 col-sm-6">
      <div class="card border-0 shadow-sm rounded-4 h-100" style="background: #f8f9fa;">
        <div class="card-body text-center py-4">
          <small class="text-muted d-block mb-3">Draft</small>
          <h2 class="fw-bold" style="color: #757575; font-size: 2.5rem;">1</h2>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== 2. SECTION FILTER DAN AKSI ===== -->
  <div class="row g-3 mb-4">
    <!-- Search Input -->
    <div class="col-md-5">
      <div class="input-group input-group-lg rounded-4 overflow-hidden">
        <span class="input-group-text bg-white border-0" style="border-radius: 8px 0 0 8px;">
          
        </span>
        <input 
          type="text" 
          class="form-control border-0 ps-3" 
          id="searchPengumuman"
          placeholder="Cari pengumuman..."
          style="border-radius: 0 8px 8px 0; font-size: 0.95rem;"
        >
      </div>
    </div>

    <!-- Filter Dropdown -->
    <div class="col-md-4">
      <select class="form-select form-select-lg rounded-4 border-0" 
              id="filterStatus" 
              style="background: white; color: #333; font-size: 0.95rem; padding: 0.75rem 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <option value="">Semua Status</option>
        <option value="Terpublikasi">Terpublikasi</option>
        <option value="Terjadwal">Terjadwal</option>
        <option value="Draft">Draft</option>
      </select>
    </div>

    <!-- Tombol Buat Baru -->
    <div class="col-md-3 d-flex">
      <button class="btn btn-lg w-100 rounded-4 fw-bold" 
              style="background: #5f3a74; color: white; border: none; font-size: 0.95rem; box-shadow: 0 2px 8px rgba(95, 58, 116, 0.3); transition: all 0.3s ease;"
              onmouseover="this.style.background='#4a2e5e'; this.style.boxShadow='0 4px 12px rgba(95, 58, 116, 0.4)';"
              onmouseout="this.style.background='#5f3a74'; this.style.boxShadow='0 2px 8px rgba(95, 58, 116, 0.3)';">
         Buat Pengumuman Baru
      </button>
    </div>
  </div>

  <!-- ===== 3. INFO BOX DISTRIBUSI EMAIL ===== -->
  <div class="alert rounded-4 mb-5" style="background: #E8EAF6; border: 1px solid #9FA8DA; padding: 1.5rem; border-radius: 16px;">
    <div style="display: flex; gap: 1rem;">
      <div style="font-size: 1.5rem; flex-shrink: 0;"></div>
      <div>
        <strong style="color: #5f3a74;">Sistem Distribusi Email:</strong>
        <div style="color: #424242; margin-top: 0.5rem; line-height: 1.6;">
          Semua pengumuman yang disetujui akan dikirim otomatis ke email resmi UNKLAB dari organisasi atau mahasiswa yang ditargetkan. Pastikan target distribusi sudah benar sebelum mempublikasikan.
        </div>
      </div>
    </div>
  </div>

  <!-- ===== 4. TABEL DAFTAR PENGUMUMAN ===== -->
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover mb-0" id="tablePengumuman" style="border-collapse: collapse;">
        <thead style="background: #f8f9fa; border-bottom: 2px solid #E0E0E0;">
          <tr>
            <th style="color: #424242; font-weight: 600; padding: 1.25rem; width: 30%;">Judul Pengumuman</th>
            <th style="color: #424242; font-weight: 600; padding: 1.25rem; width: 16%;">Kategori</th>
            <th style="color: #424242; font-weight: 600; padding: 1.25rem; width: 18%;">Target</th>
            <th style="color: #424242; font-weight: 600; padding: 1.25rem; width: 18%;">Jadwal Publish</th>
            <th style="color: #424242; font-weight: 600; padding: 1.25rem; width: 12%;">Status</th>
            <th style="color: #424242; font-weight: 600; padding: 1.25rem; text-align: center; width: 6%;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- Row 1 -->
          <tr style="border-bottom: 1px solid #EEEEEE; transition: background 0.2s ease;">
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div>
                <a href="#" style="color: #5f3a74; text-decoration: none; font-weight: 600; font-size: 1rem;">Pendaftaran Beasiswa Prestasi 2026</a>
              </div>
              <small style="color: #9E9E9E;">Dibuat: 20 Januari 2026</small>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">Akademik</td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">
               Semua Organisasi
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div style="color: #424242;">16 Desember 2024</div>
              <div style="color: #9E9E9E; font-size: 0.9rem;">08:00</div>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <span class="badge rounded-pill" style="background: #C8E6C9; color: #1B5E20; font-weight: 600;">Terpublikasi</span>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; text-align: center;">
              <div style="display: flex; gap: 8px; justify-content: center;">
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #5f3a74; transition: all 0.2s;" 
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Lihat"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #757575; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'; this.style.color='#424242';"
                   onmouseout="this.style.transform='scale(1)'; this.style.color='#757575';" title="Edit"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #E53935; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Hapus"></a>
              </div>
            </td>
          </tr>

          <!-- Row 2 -->
          <tr style="border-bottom: 1px solid #EEEEEE; transition: background 0.2s ease;">
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div>
                <a href="#" style="color: #5f3a74; text-decoration: none; font-weight: 600; font-size: 1rem;">Undangan Webinar Internasional</a>
              </div>
              <small style="color: #9E9E9E;">Dibuat: 18 Januari 2026</small>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">Non-Akademik</td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">
               Fakultas &amp; Mahasiswa
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div style="color: #424242;">22 Desember 2024</div>
              <div style="color: #9E9E9E; font-size: 0.9rem;">14:30</div>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <span class="badge rounded-pill" style="background: #BBDEFB; color: #0D47A1; font-weight: 600;">Terjadwal</span>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; text-align: center;">
              <div style="display: flex; gap: 8px; justify-content: center;">
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #5f3a74; transition: all 0.2s;" 
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Lihat"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #757575; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'; this.style.color='#424242';"
                   onmouseout="this.style.transform='scale(1)'; this.style.color='#757575';" title="Edit"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #E53935; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Hapus"></a>
              </div>
            </td>
          </tr>

          <!-- Row 3 -->
          <tr style="border-bottom: 1px solid #EEEEEE; transition: background 0.2s ease;">
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div>
                <a href="#" style="color: #5f3a74; text-decoration: none; font-weight: 600; font-size: 1rem;">Pengumuman Libur Nasional 2026</a>
              </div>
              <small style="color: #9E9E9E;">Dibuat: 10 Januari 2026</small>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">Umum</td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">
               Semua Civitas
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div style="color: #9E9E9E;">--</div>
              <div style="color: #9E9E9E; font-size: 0.9rem;">&nbsp;</div>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <span class="badge rounded-pill" style="background: #E0E0E0; color: #424242; font-weight: 600;">Draft</span>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; text-align: center;">
              <div style="display: flex; gap: 8px; justify-content: center;">
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #5f3a74; transition: all 0.2s;" 
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Lihat"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #757575; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'; this.style.color='#424242';"
                   onmouseout="this.style.transform='scale(1)'; this.style.color='#757575';" title="Edit"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #E53935; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Hapus"></a>
              </div>
            </td>
          </tr>

          <!-- Row 4 -->
          <tr style="border-bottom: 1px solid #EEEEEE; transition: background 0.2s ease;">
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div>
                <a href="#" style="color: #5f3a74; text-decoration: none; font-weight: 600; font-size: 1rem;">Pemilihan Pengurus Himpunan 2026</a>
              </div>
              <small style="color: #9E9E9E;">Dibuat: 25 Januari 2026</small>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">Organisasi</td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">
               Pengurus &amp; Anggota
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div style="color: #424242;">28 Februari 2026</div>
              <div style="color: #9E9E9E; font-size: 0.9rem;">10:00</div>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <span class="badge rounded-pill" style="background: #BBDEFB; color: #0D47A1; font-weight: 600;">Terjadwal</span>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; text-align: center;">
              <div style="display: flex; gap: 8px; justify-content: center;">
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #5f3a74; transition: all 0.2s;" 
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Lihat"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #757575; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'; this.style.color='#424242';"
                   onmouseout="this.style.transform='scale(1)'; this.style.color='#757575';" title="Edit"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #E53935; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Hapus"></a>
              </div>
            </td>
          </tr>

          <!-- Row 5 -->
          <tr style="border-bottom: 1px solid #EEEEEE; transition: background 0.2s ease;">
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div>
                <a href="#" style="color: #5f3a74; text-decoration: none; font-weight: 600; font-size: 1rem;">Informasi Registrasi PKM 2026</a>
              </div>
              <small style="color: #9E9E9E;">Dibuat: 05 Februari 2026</small>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">Akademik</td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">
               Mahasiswa S1
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div style="color: #424242;">15 Maret 2026</div>
              <div style="color: #9E9E9E; font-size: 0.9rem;">09:00</div>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <span class="badge rounded-pill" style="background: #C8E6C9; color: #1B5E20; font-weight: 600;">Terpublikasi</span>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; text-align: center;">
              <div style="display: flex; gap: 8px; justify-content: center;">
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #5f3a74; transition: all 0.2s;" 
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Lihat"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #757575; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'; this.style.color='#424242';"
                   onmouseout="this.style.transform='scale(1)'; this.style.color='#757575';" title="Edit"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #E53935; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Hapus"></a>
              </div>
            </td>
          </tr>

          <!-- Row 6 -->
          <tr style="border-bottom: 1px solid #EEEEEE; transition: background 0.2s ease;">
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div>
                <a href="#" style="color: #5f3a74; text-decoration: none; font-weight: 600; font-size: 1rem;">Pengumuman Jadwal Kuliah Semester Genap</a>
              </div>
              <small style="color: #9E9E9E;">Dibuat: 27 Januari 2026</small>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">Akademik</td>
            <td style="padding: 1.25rem; vertical-align: middle; color: #424242;">
               Semua Mahasiswa
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <div style="color: #9E9E9E;">--</div>
              <div style="color: #9E9E9E; font-size: 0.9rem;">&nbsp;</div>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle;">
              <span class="badge rounded-pill" style="background: #E0E0E0; color: #424242; font-weight: 600;">Draft</span>
            </td>
            <td style="padding: 1.25rem; vertical-align: middle; text-align: center;">
              <div style="display: flex; gap: 8px; justify-content: center;">
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #5f3a74; transition: all 0.2s;" 
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Lihat"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #757575; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'; this.style.color='#424242';"
                   onmouseout="this.style.transform='scale(1)'; this.style.color='#757575';" title="Edit"></a>
                <a href="#" style="font-size: 1.2rem; cursor: pointer; text-decoration: none; color: #E53935; transition: all 0.2s;"
                   onmouseover="this.style.transform='scale(1.3)'"
                   onmouseout="this.style.transform='scale(1)'" title="Hapus"></a>
              </div>
            </td>
          </tr>

        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- ===== STYLES ===== -->
<style>
  /* Hover effect pada row tabel */
  #tablePengumuman tbody tr:hover {
    background-color: #F5F5F5 !important;
  }

  /* Styling untuk link */
  a {
    cursor: pointer;
  }

  /* Responsive adjustments -->
  @media (max-width: 768px) {
    .display-6 {
      font-size: 1.75rem;
    }

    .row.g-3 {
      margin-bottom: 1rem;
    }

    .card {
      margin-bottom: 0.5rem;
    }
  }
</style>

<!-- ===== JAVASCRIPT: SEARCH & FILTER ===== -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchPengumuman');
    const filterDropdown = document.getElementById('filterStatus');
    const tableRows = document.querySelectorAll('#tablePengumuman tbody tr');

    function applyFilter() {
      const searchValue = searchInput.value.toLowerCase().trim();
      const filterValue = filterDropdown.value;

      tableRows.forEach(row => {
        // Ambil data dari setiap cell
        const judul = row.cells[0].innerText.toLowerCase();
        const kategori = row.cells[1].innerText.toLowerCase();
        const target = row.cells[2].innerText.toLowerCase();
        const status = row.cells[4].innerText.trim();

        // Check search match
        const matchesSearch = searchValue === '' || 
                             judul.includes(searchValue) || 
                             kategori.includes(searchValue) || 
                             target.includes(searchValue);

        // Check filter match
        const matchesFilter = filterValue === '' || status.includes(filterValue);

        // Show or hide row
        if (matchesSearch && matchesFilter) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    // Event listeners
    searchInput.addEventListener('input', applyFilter);
    filterDropdown.addEventListener('change', applyFilter);
  });
</script>

