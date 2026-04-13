<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\OrganisasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IzinKegiatanWorkflowController;
use App\Http\Controllers\KemahasiswaanWorkflowController;
use Illuminate\Http\Request;

Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Portal Login Routes
|--------------------------------------------------------------------------
*/
Route::get('/portal', function () {
    return redirect()->route('login');
})->name('portal.index');

Route::get('/portal/login', function () {
    return redirect()->route('login');
})->name('portal.login');

Route::get('/portal/internal-login', function () {
    return redirect()->route('login');
})->name('portal.internal.login');

Route::post('/portal/login', [AuthController::class, 'login'])->name('portal.login.perform');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Named Targets)
|--------------------------------------------------------------------------
*/
Route::view('/dashboard', 'portal.pengurus.dashboard')->name('dashboard');
Route::view('/dashboard/admin', 'pages.admin.dashboard')->name('dashboard.admin');
Route::view('/dashboard/kemahasiswaan', 'pages.kemahasiswaan.dashboard')->name('dashboard.kemahasiswaan');
Route::view('/dashboard/pengurus', 'portal.pengurus.dashboard')->name('dashboard.pengurus');
Route::view('/dashboard/mahasiswa', 'mahasiswa.beranda')->name('dashboard.mahasiswa');

/*
|--------------------------------------------------------------------------
| Portal Internal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('portal/admin')->name('portal.admin.')->group(function () {
    Route::view('/', 'pages.admin.dashboard')->name('dashboard');
});

Route::prefix('portal/kemahasiswaan')->name('portal.kemahasiswaan.')->group(function () {
    Route::view('/', 'pages.kemahasiswaan.dashboard')->name('dashboard');
    Route::get('/organisasi', [KemahasiswaanWorkflowController::class, 'organisasiIndex'])->name('organisasi');
    Route::post('/organisasi/akun', [KemahasiswaanWorkflowController::class, 'storeAkunUKM'])->name('organisasi.akun.store');
    Route::post('/organisasi/akun/{id}/update', [KemahasiswaanWorkflowController::class, 'updateAkunUKM'])->name('organisasi.akun.update');
    Route::post('/organisasi/akun/{id}/reset-password', [KemahasiswaanWorkflowController::class, 'resetPasswordAkunUKM'])->name('organisasi.akun.reset-password');
    Route::post('/organisasi/akun/{id}/deactivate', [KemahasiswaanWorkflowController::class, 'deactivateAkunUKM'])->name('organisasi.akun.deactivate');
    Route::get('/pengajuan', [IzinKegiatanWorkflowController::class, 'kemahasiswaanIndex'])->name('pengajuan');
    Route::post('/pengajuan/{id}/review', [IzinKegiatanWorkflowController::class, 'review'])->name('pengajuan.review');
    Route::post('/laporan/{id}/review', [IzinKegiatanWorkflowController::class, 'reviewLaporan'])->name('laporan.review');
    Route::post('/jadwal', [IzinKegiatanWorkflowController::class, 'storeJadwal'])->name('jadwal.store');
    Route::get('/pengumuman', [KemahasiswaanWorkflowController::class, 'pengumumanIndex'])->name('pengumuman');
    Route::post('/pengumuman', [KemahasiswaanWorkflowController::class, 'storePengumuman'])->name('pengumuman.store');
    Route::post('/pengumuman/{id}/email-review', [KemahasiswaanWorkflowController::class, 'reviewIzinPengumumanEmail'])->name('pengumuman.email-review');
    Route::view('/notifikasi', 'pages.portal.kemahasiswaan.notifikasi')->name('notifikasi');
});

Route::prefix('portal/pengurus')->name('portal.pengurus.')->group(function () {
    Route::view('/', 'portal.pengurus.dashboard')->name('dashboard');
    Route::view('/events', 'portal.pengurus.events')->name('events');
    Route::get('/events/create', [IzinKegiatanWorkflowController::class, 'eventForm'])->name('events.create');
    Route::post('/events', [IzinKegiatanWorkflowController::class, 'storeEvent'])->name('events.store');
    Route::view('/events/{id}', 'pages.pengurus.events.detail')->name('events.detail');

    Route::view('/announcements', 'portal.pengurus.announcements')->name('announcements');
    Route::view('/announcements/create', 'pages.pengurus.announcements.form')->name('announcements.create');

    Route::view('/lostandfound', 'portal.pengurus.lostandfound')->name('lostandfound');
    Route::get('/proposals', [IzinKegiatanWorkflowController::class, 'pengurusIndex'])->name('proposals');
    Route::post('/proposals', [IzinKegiatanWorkflowController::class, 'storePengajuan'])->name('proposals.store');
    Route::post('/proposals/{id}/submit', [IzinKegiatanWorkflowController::class, 'submit'])->name('proposals.submit');
    Route::post('/reports', [IzinKegiatanWorkflowController::class, 'storeLaporan'])->name('reports.store');
    Route::post('/reports/{id}/submit', [IzinKegiatanWorkflowController::class, 'submitLaporan'])->name('reports.submit');
    Route::view('/members', 'portal.pengurus.members')->name('members');
    Route::view('/applications', 'portal.pengurus.applications')->name('applications');

    Route::get('/settings', [IzinKegiatanWorkflowController::class, 'pengurusSettings'])->name('settings');
    Route::post('/settings/profile', [IzinKegiatanWorkflowController::class, 'updateProfilUKM'])->name('settings.profile.update');
    Route::view('/reports', 'pages.pengurus.dashboard-detail')->name('reports');
    Route::view('/submissions', 'pages.pengurus.dashboard-advanced')->name('submissions');
});

/*
|--------------------------------------------------------------------------
| Blade Frontend Routes (Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::view('/', 'mahasiswa.beranda')->name('beranda');
    Route::get('/organisasi', [OrganisasiController::class, 'index'])->name('organisasi');
    Route::get('/organisasi', [OrganisasiController::class, 'index'])->name('organisasi.index');
    Route::get('/organisasi/{id}', [OrganisasiController::class, 'show'])->name('organisasi.show');
    Route::view('/event', 'mahasiswa.event')->name('event');
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
    Route::view('/tentang', 'mahasiswa.tentang')->name('tentang');
});

Route::view('/lost-found', 'lost-found.index')->name('mahasiswa.lost-found');

/*
|--------------------------------------------------------------------------
| Legacy Blade Route Compatibility
|--------------------------------------------------------------------------
*/
Route::get('/organisasi', [OrganisasiController::class, 'index'])->name('organisasi.index');
Route::get('/organisasi/{id}', [OrganisasiController::class, 'show'])->name('organisasi.show');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
Route::view('/kegiatan', 'mahasiswa.event')->name('kegiatan.index');

/*
|--------------------------------------------------------------------------
| Legacy Dashboard Route Names Used by Blade Components
|--------------------------------------------------------------------------
*/
Route::get('/profil', function () {
    return view('pages.pengurus.profil-organisasi');
})->name('profil.show');

Route::get('/events', function () {
    return redirect()->route('portal.pengurus.events');
})->name('events.index');

Route::get('/events/create', [IzinKegiatanWorkflowController::class, 'eventForm'])->name('events.create');

Route::get('/events/{event}', function ($event) {
    return view('pages.pengurus.events.detail');
})->name('events.show');

Route::get('/events/{event}/edit', function ($event) {
    return view('pages.pengurus.events.form');
})->name('events.edit');

Route::get('/announcements', function () {
    return redirect()->route('portal.pengurus.announcements');
})->name('announcements.index');

Route::get('/announcements/create', function () {
    return view('pages.pengurus.announcements.form');
})->name('announcements.create');

Route::get('/announcements/{announcement}', function ($announcement) {
    return view('portal.pengurus.announcements');
})->name('announcements.show');

Route::get('/announcements/{announcement}/edit', function ($announcement) {
    return view('pages.pengurus.announcements.form');
})->name('announcements.edit');

Route::get('/proposals', function () {
    return redirect()->route('portal.pengurus.proposals');
})->name('proposals.index');

Route::get('/proposals/{proposal}', function ($proposal) {
    return view('portal.pengurus.proposals');
})->name('proposals.show');

Route::get('/proposals/{proposal}/edit', function ($proposal) {
    return view('portal.pengurus.proposals');
})->name('proposals.edit');

Route::post('/proposals/{proposal}/submit', function (Request $request, $proposal) {
    return back()->with('success', 'Pengajuan berhasil disubmit');
})->name('proposals.submit');

Route::post('/proposals/{proposal}/approve', function (Request $request, $proposal) {
    return back()->with('success', 'Pengajuan berhasil disetujui');
})->name('proposals.approve');

Route::post('/proposals/{proposal}/reject', function (Request $request, $proposal) {
    return back()->with('success', 'Pengajuan berhasil ditolak');
})->name('proposals.reject');

Route::get('/messages', function () {
    return view('portal.pengurus.applications');
})->name('messages.index');

Route::get('/lostfound', function () {
    return redirect()->route('mahasiswa.lost-found');
})->name('lostfound.index');

/*
|--------------------------------------------------------------------------
| API ENDPOINTS
|--------------------------------------------------------------------------
*/
Route::prefix('api')->group(function () {
    Route::get('/pengumuman', function () {
        $items = [
            ['id' => 1, 'judul' => 'Info Semester Baru', 'kategori' => 'Akademik', 'author' => 'Rektorat', 'date' => '2026-03-01'],
            ['id' => 2, 'judul' => 'Open Recruitment Organisasi', 'kategori' => 'Organisasi', 'author' => 'Kemahasiswaan', 'date' => '2026-03-05'],
            ['id' => 3, 'judul' => 'Jadwal Event Kampus', 'kategori' => 'Event', 'author' => 'BEM', 'date' => '2026-03-10'],
        ];

        return response()->json($items);
    })->name('api.pengumuman.index');

    Route::get('/pengumuman/{id}', function ($id) {
        $items = [
            1 => ['id' => 1, 'judul' => 'Info Semester Baru', 'konten' => 'Perkuliahan semester baru dimulai sesuai kalender akademik.', 'kategori' => 'Akademik', 'author' => 'Rektorat', 'date' => '2026-03-01'],
            2 => ['id' => 2, 'judul' => 'Open Recruitment Organisasi', 'konten' => 'Pendaftaran anggota organisasi dibuka hingga akhir bulan.', 'kategori' => 'Organisasi', 'author' => 'Kemahasiswaan', 'date' => '2026-03-05'],
            3 => ['id' => 3, 'judul' => 'Jadwal Event Kampus', 'konten' => 'Rangkaian event kampus diumumkan untuk seluruh mahasiswa.', 'kategori' => 'Event', 'author' => 'BEM', 'date' => '2026-03-10'],
        ];

        abort_unless(isset($items[(int) $id]), 404);

        return response()->json($items[(int) $id]);
    })->name('api.pengumuman.detail');

    Route::get('/organisasi', function () {
        $organizations = collect(require resource_path('data/organizationData.php'))
            ->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'tagline' => $item['tagline'] ?? null,
                    'activeMembers' => $item['activeMembers'] ?? 0,
                ];
            })
            ->values();

        return response()->json($organizations);
    })->name('api.organisasi.index');

    Route::get('/organisasi/{id}', function ($id) {
        $organizations = collect(require resource_path('data/organizationData.php'));
        $item = $organizations->firstWhere('id', (int) $id);

        abort_if(!$item, 404);

        return response()->json($item);
    })->name('api.organisasi.show');

    Route::get('/lost-found', function () {
        return response()->json([
            ['id' => 1, 'title' => 'Dompet Kulit Hitam', 'type' => 'lost', 'status' => 'active', 'location' => 'Aula Utama'],
            ['id' => 2, 'title' => 'Kunci Motor', 'type' => 'found', 'status' => 'active', 'location' => 'Parkiran Timur'],
        ]);
    })->name('api.lost-found.index');

    Route::get('/lost-found/{id}', function ($id) {
        $items = [
            1 => ['id' => 1, 'title' => 'Dompet Kulit Hitam', 'description' => 'Dompet kulit hitam berisi kartu identitas.', 'type' => 'lost', 'status' => 'active', 'location' => 'Aula Utama'],
            2 => ['id' => 2, 'title' => 'Kunci Motor', 'description' => 'Satu set kunci motor dengan gantungan biru.', 'type' => 'found', 'status' => 'active', 'location' => 'Parkiran Timur'],
        ];

        abort_unless(isset($items[(int) $id]), 404);

        return response()->json($items[(int) $id]);
    })->name('api.lost-found.detail');
});

/*
|--------------------------------------------------------------------------
| Laravel Blade fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return view('welcome');
});
