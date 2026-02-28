@extends('layouts.pengurus')

@section('title', 'Profil Organisasi')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <h1>Profil Organisasi</h1>
    <p>Kelola informasi dan data anggota organisasi UFO</p>
</div>

<!-- Statistics -->
<div class="row mb-5">
    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-users"></i></div>
            <div class="card-stat-number">142</div>
            <div class="card-stat-label">Total Anggota</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #22C55E 0%, #15803D 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-user-tie"></i></div>
            <div class="card-stat-number">12</div>
            <div class="card-stat-label">Pengurus</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #FBBF24 0%, #D97706 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="card-stat-number">130</div>
            <div class="card-stat-label">Anggota Aktif</div>
        </div>
    </div>

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card card-dashboard card-stat" style="background: linear-gradient(135deg, #A78BFA 0%, #7C3AED 100%); color: white;">
            <div class="card-stat-icon"><i class="fas fa-ban"></i></div>
            <div class="card-stat-number">12</div>
            <div class="card-stat-label">Anggota Nonaktif</div>
        </div>
    </div>
</div>

<!-- Organization Info Section -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0"><i class="fas fa-building"></i> Informasi Organisasi</h5>
                    <button class="btn btn-sm btn-primary-custom" id="editOrgBtn">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
                
                <div id="orgInfo" class="org-info">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Nama Organisasi</label>
                            <p>UFO - University Forum Organization</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Singkatan</label>
                            <p>UFO</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email</label>
                            <p>ufo.org@unklab.ac.id</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Telepon</label>
                            <p>+62 831-1234-5678</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="fw-bold">Deskripsi</label>
                            <p>Organisasi forum mahasiswa yang fokus pada pengembangan keterampilan dan kolaborasi antar mahasiswa.</p>
                        </div>
                    </div>
                </div>

                <div id="orgForm" class="org-form" style="display: none;">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Organisasi</label>
                                <input type="text" class="form-control" value="UFO - University Forum Organization">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Singkatan</label>
                                <input type="text" class="form-control" value="UFO">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="ufo.org@unklab.ac.id">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="tel" class="form-control" value="+62 831-1234-5678">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" rows="3">Organisasi forum mahasiswa yang fokus pada pengembangan keterampilan dan kolaborasi antar mahasiswa.</textarea>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-primary-custom" id="saveOrgBtn">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                                <button type="button" class="btn btn-secondary ms-2" id="cancelOrgBtn">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Members List -->
<div class="row">
    <div class="col-12">
        <div class="card card-dashboard">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0"><i class="fas fa-users"></i> Daftar Anggota</h5>
                    <button class="btn btn-sm btn-primary-custom">
                        <i class="fas fa-plus"></i> Tambah Anggota
                    </button>
                </div>

                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <input type="text" class="form-control" placeholder="Cari nama atau NIM...">
                    </div>
                    <div class="col-md-6">
                        <select class="form-select">
                            <option>Semua Status</option>
                            <option>Aktif</option>
                            <option>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Posisi</th>
                                <th>Status</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Ahmad Rifki Ramadan</strong></td>
                                <td>20211001</td>
                                <td><span class="badge badge-custom badge-primary">Ketua</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>rifki@email.com</td>
                                <td>
                                    <button class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Siti Nurhaliza</strong></td>
                                <td>20211002</td>
                                <td><span class="badge badge-custom badge-primary">Wakil Ketua</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>siti.nur@email.com</td>
                                <td>
                                    <button class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Muhammad Hendra</strong></td>
                                <td>20211003</td>
                                <td><span class="badge badge-custom badge-primary">Sekretaris</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>hendra.m@email.com</td>
                                <td>
                                    <button class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Budi Santoso</strong></td>
                                <td>20211004</td>
                                <td><span class="badge badge-custom badge-primary">Bendahara</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>budi.s@email.com</td>
                                <td>
                                    <button class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Eka Putri Indah</strong></td>
                                <td>20211005</td>
                                <td><span class="badge badge-custom badge-warning">Anggota</span></td>
                                <td><span class="badge bg-secondary">Nonaktif</span></td>
                                <td>eka.putri@email.com</td>
                                <td>
                                    <button class="btn btn-sm btn-info me-2"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
    document.getElementById('editOrgBtn').addEventListener('click', function() {
        document.getElementById('orgInfo').style.display = 'none';
        document.getElementById('orgForm').style.display = 'block';
    });

    document.getElementById('cancelOrgBtn').addEventListener('click', function() {
        document.getElementById('orgInfo').style.display = 'block';
        document.getElementById('orgForm').style.display = 'none';
    });

    document.getElementById('saveOrgBtn').addEventListener('click', function() {
        alert('Data organisasi berhasil disimpan!');
        document.getElementById('orgInfo').style.display = 'block';
        document.getElementById('orgForm').style.display = 'none';
    });
</script>
@endsection
