<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Pengurus\IzinKegiatanWorkflowController;
use App\Http\Controllers\Kemahasiswaan\KemahasiswaanWorkflowController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

Route::get('/', [MahasiswaController::class, 'organisasiIndex'])->name('home');

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
| Internal Login Alias Routes (No /portal Prefix)
|--------------------------------------------------------------------------
*/
Route::get('/internal', function (Request $request) {
    return redirect()->route('login', $request->only('role'));
})->name('portal.index');

Route::get('/internal-login', function (Request $request) {
    return redirect()->route('login', $request->only('role'));
})->name('portal.login');

Route::get('/internal/auth-login', function (Request $request) {
    return redirect()->route('login', $request->only('role'));
})->name('portal.internal.login');

Route::post('/internal-login', [AuthController::class, 'login'])->name('portal.login.perform');

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Named Targets)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [IzinKegiatanWorkflowController::class, 'pengurusDashboard'])
    ->middleware(['auth', 'role:pengurus'])
    ->name('dashboard');
Route::get('/dashboard/kemahasiswaan', [KemahasiswaanWorkflowController::class, 'dashboard'])
    ->middleware(['auth', 'role:kemahasiswaan'])
    ->name('dashboard.kemahasiswaan');
Route::get('/dashboard/pengurus', [IzinKegiatanWorkflowController::class, 'pengurusDashboard'])
    ->middleware(['auth', 'role:pengurus'])
    ->name('dashboard.pengurus');
Route::get('/dashboard/mahasiswa', [MahasiswaController::class, 'beranda'])->name('dashboard.mahasiswa');

/*
|--------------------------------------------------------------------------
| Portal Internal Routes
|--------------------------------------------------------------------------
*/
Route::prefix('kemahasiswaan')->name('portal.kemahasiswaan.')->middleware(['auth', 'role:kemahasiswaan'])->group(function () {
    Route::get('/', [KemahasiswaanWorkflowController::class, 'dashboard'])->name('dashboard');
    Route::get('/organisasi', [KemahasiswaanWorkflowController::class, 'organisasiIndex'])->name('organisasi');
    Route::post('/organisasi', [KemahasiswaanWorkflowController::class, 'storeOrganisasi'])->name('organisasi.store');
    Route::post('/organisasi/{id}/update', [KemahasiswaanWorkflowController::class, 'updateOrganisasi'])->name('organisasi.update');
    Route::delete('/organisasi/{id}', [KemahasiswaanWorkflowController::class, 'deactivateOrganisasi'])->name('organisasi.destroy');
    Route::get('/kontak', [KemahasiswaanWorkflowController::class, 'kontakPengurusIndex'])->name('kontak');
    Route::get('/kalender', [KemahasiswaanWorkflowController::class, 'kalenderKegiatanIndex'])->name('kalender');
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
    Route::get('/notifikasi', [KemahasiswaanWorkflowController::class, 'notifikasiIndex'])->name('notifikasi');
});

Route::prefix('pengurus')->name('portal.pengurus.')->middleware(['auth', 'role:pengurus'])->group(function () {
    Route::get('/', [IzinKegiatanWorkflowController::class, 'pengurusDashboard'])->name('dashboard');
    Route::redirect('/profil', '/pengurus/members')->name('profil');
    Route::redirect('/event', '/pengurus/events')->name('event');
    Route::redirect('/pengumuman', '/pengurus/announcements')->name('pengumuman');
    Route::redirect('/pengajuan-laporan', '/pengurus/proposals')->name('pengajuan-laporan');
    Route::redirect('/kontak', '/pengurus/applications')->name('kontak');
    Route::redirect('/lost-found', '/pengurus/lostandfound')->name('lost-found');

    Route::get('/events', [IzinKegiatanWorkflowController::class, 'pengurusEvents'])->name('events');
    Route::get('/events/create', [IzinKegiatanWorkflowController::class, 'eventForm'])->name('events.create');
    Route::post('/events', [IzinKegiatanWorkflowController::class, 'storeEvent'])->name('events.store');
    Route::post('/events/{id}/update', [IzinKegiatanWorkflowController::class, 'updateEvent'])->name('events.update');
    Route::get('/events/{id}', [IzinKegiatanWorkflowController::class, 'pengurusEventDetail'])->name('events.detail');

    Route::post('/news', [IzinKegiatanWorkflowController::class, 'storeNews'])->name('news.store');

    Route::get('/announcements', [IzinKegiatanWorkflowController::class, 'pengurusAnnouncements'])->name('announcements');
    Route::get('/announcements/create', [IzinKegiatanWorkflowController::class, 'pengurusAnnouncementForm'])->name('announcements.create');
    Route::post('/announcements', [IzinKegiatanWorkflowController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::post('/announcements/{id}/update', [IzinKegiatanWorkflowController::class, 'updateAnnouncement'])->name('announcements.update');

    Route::get('/lostandfound', [IzinKegiatanWorkflowController::class, 'pengurusLostAndFound'])->name('lostandfound');
    Route::post('/lostandfound', [IzinKegiatanWorkflowController::class, 'storeLostFound'])->name('lostandfound.store');
    Route::get('/proposals', [IzinKegiatanWorkflowController::class, 'pengurusIndex'])->name('proposals');
    Route::post('/proposals', [IzinKegiatanWorkflowController::class, 'storePengajuan'])->name('proposals.store');
    Route::post('/proposals/{id}/submit', [IzinKegiatanWorkflowController::class, 'submit'])->name('proposals.submit');
    Route::post('/reports', [IzinKegiatanWorkflowController::class, 'storeLaporan'])->name('reports.store');
    Route::post('/reports/{id}/submit', [IzinKegiatanWorkflowController::class, 'submitLaporan'])->name('reports.submit');
    Route::get('/members', [IzinKegiatanWorkflowController::class, 'pengurusMembers'])->name('members');
    Route::post('/members/profile', [IzinKegiatanWorkflowController::class, 'updatePengurusMembersProfile'])->name('members.profile.update');
    Route::get('/applications', [IzinKegiatanWorkflowController::class, 'pengurusApplications'])->name('applications');

    Route::get('/settings', [IzinKegiatanWorkflowController::class, 'pengurusSettings'])->name('settings');
    Route::post('/settings/profile', [IzinKegiatanWorkflowController::class, 'updateProfilUKM'])->name('settings.profile.update');
    Route::redirect('/reports', '/pengurus/proposals')->name('reports');
    Route::redirect('/submissions', '/pengurus/proposals')->name('submissions');
});

/*
|--------------------------------------------------------------------------
| Blade Frontend Routes (Mahasiswa)
|--------------------------------------------------------------------------
*/
Route::permanentRedirect('/mahasiswa', '/');
Route::get('/mahasiswa/organisasi', [MahasiswaController::class, 'organisasiIndex'])->name('mahasiswa.organisasi.redirect');

Route::get('/organisasi', [MahasiswaController::class, 'organisasiIndex'])->name('mahasiswa.organisasi.index');
Route::get('/organisasi/{id}', [MahasiswaController::class, 'organisasiShow'])->name('mahasiswa.organisasi.show');
Route::get('/organisasi/{id}/daftar', [MahasiswaController::class, 'organisasiDaftar'])->name('mahasiswa.organisasi.daftar');
Route::get('/organisasi/{orgId}/event/{eventId}', [MahasiswaController::class, 'organisasiEventShow'])->name('mahasiswa.organisasi.event.detail');

Route::get('/event', [MahasiswaController::class, 'eventIndex'])->name('mahasiswa.event');
Route::get('/event/{id}', [MahasiswaController::class, 'eventShow'])->name('mahasiswa.event.show');

Route::get('/lost-found', [MahasiswaController::class, 'lostFoundIndex'])->name('mahasiswa.lost-found');
Route::post('/lost-found/report', [MahasiswaController::class, 'reportLostFound'])->name('mahasiswa.lost-found.report');

Route::get('/pengumuman', [MahasiswaController::class, 'pengumumanIndex'])->name('mahasiswa.pengumuman');
Route::get('/pengumuman/{id}', [MahasiswaController::class, 'pengumumanShow'])->name('mahasiswa.pengumuman.show');

Route::get('/tentang', [MahasiswaController::class, 'tentang'])->name('mahasiswa.tentang');

Route::get('/kegiatan', [MahasiswaController::class, 'eventIndex'])->name('kegiatan.index');

/*
|--------------------------------------------------------------------------
| Legacy Dashboard Route Names Used by Blade Components
|--------------------------------------------------------------------------
*/
Route::get('/profil', function () {
    return redirect()->route('portal.pengurus.members');
})->name('profil.show');

Route::get('/events', function () {
    return redirect()->route('portal.pengurus.events');
})->name('events.index');

Route::get('/events/create', [IzinKegiatanWorkflowController::class, 'eventForm'])->name('events.create');

Route::get('/events/{event}', function ($event) {
    return redirect()->route('portal.pengurus.events.detail', ['id' => $event]);
})->name('events.show');

Route::get('/events/{event}/edit', function ($event) {
    return redirect()->route('portal.pengurus.events.create');
})->name('events.edit');

Route::get('/announcements', function () {
    return redirect()->route('portal.pengurus.announcements');
})->name('announcements.index');

Route::get('/announcements/create', function () {
    return app(IzinKegiatanWorkflowController::class)->pengurusAnnouncementForm(request());
})->name('announcements.create');

Route::get('/announcements/{announcement}', function ($announcement) {
    return redirect()->route('portal.pengurus.announcements');
})->name('announcements.show');

Route::get('/announcements/{announcement}/edit', function ($announcement) {
    return app(IzinKegiatanWorkflowController::class)->pengurusAnnouncementForm(request());
})->name('announcements.edit');

Route::get('/proposals', function () {
    return redirect()->route('portal.pengurus.proposals');
})->name('proposals.index');

Route::get('/proposals/{proposal}', function ($proposal) {
    return redirect()->route('portal.pengurus.proposals');
})->name('proposals.show');

Route::get('/proposals/{proposal}/edit', function ($proposal) {
    return redirect()->route('portal.pengurus.proposals');
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
    return redirect()->route('portal.pengurus.applications');
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
    $hasTable = static fn (string $table): bool => Schema::hasTable($table);

    $buildAnnouncementQuery = static function (array $baseColumns) use ($hasTable) {
        $query = DB::table('kemahasiswaan_announcements as ann');
        $hasAccounts = $hasTable('kemahasiswaan_ukm_accounts');
        $hasOrganizations = $hasTable('organizations');

        if ($hasAccounts) {
            $query->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id');
        }

        if ($hasAccounts && $hasOrganizations) {
            $query->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id');
        }

        $query->select($baseColumns);
        $query->selectRaw($hasAccounts ? 'akun.name as account_name' : 'NULL as account_name');
        $query->selectRaw($hasAccounts && $hasOrganizations ? 'org.name as organization_name' : 'NULL as organization_name');

        return $query;
    };

    $extractAnnouncementMeta = static function ($item): array {
        $author = $item->organization_name ?: ($item->account_name ?: 'Kemahasiswaan');
        $dateValue = $item->publish_at ?: $item->created_at;

        return [
            'author' => $author,
            'date' => $dateValue ? Carbon::parse((string) $dateValue)->toDateString() : null,
        ];
    };

    $resolveLostFoundColumns = static function (): array {
        $columns = Schema::getColumnListing('lost_found_items');
        $hasColumn = static fn (string $column): bool => in_array($column, $columns, true);

        return [
            'title' => $hasColumn('item_name') ? 'item_name' : ($hasColumn('title') ? 'title' : ($hasColumn('name') ? 'name' : null)),
            'description' => $hasColumn('description') ? 'description' : null,
            'type' => $hasColumn('type') ? 'type' : null,
            'status' => $hasColumn('status') ? 'status' : null,
            'location' => $hasColumn('location_found') ? 'location_found' : ($hasColumn('location') ? 'location' : null),
        ];
    };

    $buildLostFoundQuery = static function (array $columns, bool $withDescription = false) {
        $query = DB::table('lost_found_items as lf')->select('lf.id');

        $query->selectRaw($columns['title'] ? 'lf.' . $columns['title'] . ' as title' : 'NULL as title');
        $query->selectRaw($columns['type'] ? 'lf.' . $columns['type'] . ' as type' : "'lost' as type");
        $query->selectRaw($columns['status'] ? 'lf.' . $columns['status'] . ' as status' : "'active' as status");
        $query->selectRaw($columns['location'] ? 'lf.' . $columns['location'] . ' as location' : 'NULL as location');

        if ($withDescription) {
            $query->selectRaw($columns['description'] ? 'lf.' . $columns['description'] . ' as description' : 'NULL as description');
        }

        return $query;
    };

    $normalizeLostFoundType = static fn ($type): string => in_array((string) $type, ['lost', 'found'], true) ? (string) $type : 'lost';

    Route::get('/pengumuman', function () use ($hasTable, $buildAnnouncementQuery, $extractAnnouncementMeta) {
        if (!$hasTable('kemahasiswaan_announcements')) {
            return response()->json([]);
        }

        $items = $buildAnnouncementQuery([
            'ann.id',
            'ann.title',
            'ann.category',
            'ann.publish_at',
            'ann.created_at',
        ])
            ->whereIn('ann.publish_status', ['published', 'scheduled', 'archived'])
            ->orderByDesc(DB::raw('COALESCE(ann.publish_at, ann.created_at)'))
            ->limit(300)
            ->get()
            ->map(function ($item) use ($extractAnnouncementMeta) {
                $meta = $extractAnnouncementMeta($item);

                return [
                    'id' => (int) $item->id,
                    'judul' => $item->title,
                    'kategori' => $item->category ?: 'Umum',
                    'author' => $meta['author'],
                    'date' => $meta['date'],
                ];
            })
            ->values();

        return response()->json($items);
    })->name('api.pengumuman.index');

    Route::get('/pengumuman/{id}', function ($id) use ($hasTable, $buildAnnouncementQuery, $extractAnnouncementMeta) {
        if (!$hasTable('kemahasiswaan_announcements')) {
            abort(404);
        }

        $item = $buildAnnouncementQuery([
            'ann.id',
            'ann.title',
            'ann.category',
            'ann.summary',
            'ann.content',
            'ann.publish_at',
            'ann.created_at',
        ])->where('ann.id', (int) $id)->first();

        abort_if(!$item, 404);

        $meta = $extractAnnouncementMeta($item);
        $content = trim((string) ($item->content ?: ''));

        return response()->json([
            'id' => (int) $item->id,
            'judul' => $item->title,
            'konten' => $content !== '' ? $content : (string) ($item->summary ?: ''),
            'kategori' => $item->category ?: 'Umum',
            'author' => $meta['author'],
            'date' => $meta['date'],
        ]);
    })->name('api.pengumuman.detail');

    Route::get('/organisasi', function () {
        if (!Schema::hasTable('organizations')) {
            return response()->json([]);
        }

        $activeMembers = [];

        if (Schema::hasTable('members')) {
            $activeMembers = DB::table('members')
                ->selectRaw('organization_id, COUNT(*) as total')
                ->where('status', 'aktif')
                ->groupBy('organization_id')
                ->pluck('total', 'organization_id')
                ->map(fn ($total) => (int) $total)
                ->all();
        }

        $organizations = DB::table('organizations')
            ->select(['id', 'name', 'description'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get()
            ->map(function ($item) use ($activeMembers) {
                return [
                    'id' => (int) $item->id,
                    'name' => $item->name,
                    'tagline' => $item->description ? Str::limit((string) $item->description, 100, '...') : null,
                    'activeMembers' => (int) ($activeMembers[(int) $item->id] ?? 0),
                ];
            })
            ->values();

        return response()->json($organizations);
    })->name('api.organisasi.index');

    Route::get('/organisasi/{id}', function ($id) {
        if (!Schema::hasTable('organizations')) {
            abort(404);
        }

        $item = DB::table('organizations')
            ->select([
                'id',
                'name',
                'shortname',
                'description',
                'vision',
                'mission',
                'email',
                'phone',
                'logo',
                'banner',
                'instagram',
                'line',
                'status',
            ])
            ->where('id', (int) $id)
            ->where('status', 'active')
            ->first();

        abort_if(!$item, 404);

        $activeMembers = 0;

        if (Schema::hasTable('members')) {
            $activeMembers = (int) DB::table('members')
                ->where('organization_id', (int) $item->id)
                ->where('status', 'aktif')
                ->count();
        }

        return response()->json([
            'id' => (int) $item->id,
            'name' => $item->name,
            'shortname' => $item->shortname,
            'tagline' => $item->description ? Str::limit((string) $item->description, 100, '...') : null,
            'description' => $item->description,
            'vision' => $item->vision,
            'mission' => $item->mission,
            'email' => $item->email,
            'phone' => $item->phone,
            'logo' => $item->logo,
            'banner' => $item->banner,
            'instagram' => $item->instagram,
            'line' => $item->line,
            'activeMembers' => $activeMembers,
        ]);
    })->name('api.organisasi.show');

    Route::get('/lost-found', function () use ($hasTable, $resolveLostFoundColumns, $buildLostFoundQuery, $normalizeLostFoundType) {
        if (!$hasTable('lost_found_items')) {
            return response()->json([]);
        }

        $items = $buildLostFoundQuery($resolveLostFoundColumns())
            ->orderByDesc('lf.id')
            ->limit(200)
            ->get()
            ->map(fn ($item) => [
                'id' => (int) $item->id,
                'title' => $item->title ?: 'Barang Tidak Dikenal',
                'type' => $normalizeLostFoundType($item->type),
                'status' => $item->status ?: 'active',
                'location' => $item->location ?: '-',
            ])->values();

        return response()->json($items);
    })->name('api.lost-found.index');

    Route::get('/lost-found/{id}', function ($id) use ($hasTable, $resolveLostFoundColumns, $buildLostFoundQuery, $normalizeLostFoundType) {
        if (!$hasTable('lost_found_items')) {
            abort(404);
        }

        $item = $buildLostFoundQuery($resolveLostFoundColumns(), true)
            ->where('lf.id', (int) $id)
            ->first();

        abort_if(!$item, 404);

        return response()->json([
            'id' => (int) $item->id,
            'title' => $item->title ?: 'Barang Tidak Dikenal',
            'description' => (string) ($item->description ?: ''),
            'type' => $normalizeLostFoundType($item->type),
            'status' => $item->status ?: 'active',
            'location' => $item->location ?: '-',
        ]);
    })->name('api.lost-found.detail');
});

/*
|--------------------------------------------------------------------------
| Laravel Blade fallback
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('home');
});
