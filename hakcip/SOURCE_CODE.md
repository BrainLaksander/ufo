# Ringkasan Source Code Utama UFO (UNKLAB Forum Organization)

Dokumen ini merupakan wujud lampiran *source code* utama (*core business logic*) dari aplikasi web terintegrasi UFO (UNKLAB Forum Organization). Kode ini mendemonstrasikan arsitektur sistem berbasis arsitektur MVC (Model-View-Controller) menggunakan *framework* Laravel. Kode di bawah ini menampilkan entitas model, logika kontrol akses, mekanisme persetujuan kegiatan, sistem pengumuman, serta fungsi autentikasi dan pengaturan akun organisasi yang dikembangkan secara spesifik untuk alur kerja Universitas Klabat.

---

## 1. Routing & Manajemen Akses Akses Navigasi Utama (`routes/web.php`)
Sistem menggunakan pendekatan rute berbasis grup dan *middleware* untuk mengamankan akses berdasarkan peran (Role-Based Access Control) seperti Mahasiswa Umum, Kemahasiswaan, dan Pengurus UKM.

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\LostFoundController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KemahasiswaanController;
use App\Http\Controllers\PengurusUkmController;
use App\Http\Controllers\PasswordResetController;

// Halaman Publik (Mahasiswa)
Route::middleware(['guest.internal'])->group(function () {
    Route::get('/', function () { /* Render halaman utama & hero banner */ });
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/pengumuman', [AnnouncementController::class, 'index'])->name('pengumuman.index');
    Route::get('/kalender', [\App\Http\Controllers\MahasiswaController::class, 'calendar'])->name('calendar.index');
    Route::get('/lost-found', [LostFoundController::class, 'index'])->name('lost-found.index');
    Route::post('/lost-found', [LostFoundController::class, 'store'])->name('lost-found.store');
});

// Autentikasi & Universal Actions
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Kemahasiswaan (Admin Pusat)
Route::middleware(['auth', 'role:kemahasiswaan'])->group(function () {
    Route::get('/kemahasiswaan', [KemahasiswaanController::class, 'index'])->name('kemahasiswaan.dashboard');
    Route::get('/kemahasiswaan/organisasi', [KemahasiswaanController::class, 'organizations']);
    Route::post('/kemahasiswaan/organisasi/{id}/toggle-status', [KemahasiswaanController::class, 'toggleStatus']);
    Route::post('/kemahasiswaan/organisasi/{id}/reset-account', [KemahasiswaanController::class, 'resetAccount']);
    Route::get('/kemahasiswaan/pengajuan-kegiatan-laporan', [KemahasiswaanController::class, 'submissions']);
    Route::post('/kemahasiswaan/pengajuan/{id}/review', [KemahasiswaanController::class, 'review']);
    Route::post('/kemahasiswaan/pengumuman/{id}/publish', [KemahasiswaanController::class, 'publishAnnouncement']);
});

// Halaman Pengurus UKM (Operator Organisasi)
Route::middleware(['auth', 'role:pengurus_ukm'])->group(function () {
    Route::get('/pengurus-ukm', [PengurusUkmController::class, 'index'])->name('pengurus-ukm.dashboard');
    Route::post('/pengurus-ukm/events', [PengurusUkmController::class, 'store']);
    Route::post('/pengurus-ukm/events/{event}/complete', [PengurusUkmController::class, 'completeEvent']);
    Route::post('/pengurus-ukm/pengajuan-laporan', [PengurusUkmController::class, 'storeSubmission']);
    Route::post('/pengurus-ukm/pengajuan-laporan/{id}/update', [PengurusUkmController::class, 'updateSubmission']);
    Route::post('/pengurus-ukm/lost-found/{id}/status', [PengurusUkmController::class, 'updateLostItemStatus']);
    Route::post('/pengurus-ukm/profil/send-reset-link', [PasswordResetController::class, 'sendResetLink']);
});
```

---

## 2. Struktur Data Utama (Models)

### `User.php`
```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return ['password' => 'hashed'];
    }

    public function organization() {
        return $this->hasOne(\App\Models\Organization::class, 'account_user_id');
    }
}
```

### `Organization.php`
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name', 'abbreviation', 'kategori','field','level','description',
        'is_open_recruitment','recruitment_link','recruitment_req',
        'logo_path','banner_path',
        'ketua_name','chair_phone','chair_email','chair_photo',
        'secretary_name','secretary_phone','secretary_email','secretary_photo',
        'treasurer_name','treasurer_phone','treasurer_email','treasurer_photo',
        'advisor_name','advisor_phone','advisor_email','advisor_photo',
        'status','account_user_id','account_email'
    ];

    public function accountUser() {
        return $this->belongsTo(\App\Models\User::class, 'account_user_id');
    }
}
```

### `ActivitySubmission.php`
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivitySubmission extends Model
{
    protected $fillable = [
        'organization_id', 'user_id', 'event_id', 'title', 'jenis_kegiatan',
        'penanggung_jawab', 'proposal_path', 'lpj_path', 'lpj_catatan', 
        'event_date', 'waktu', 'lokasi', 'estimasi_peserta', 'kind', 'status', 'revision_note'
    ];

    protected $casts = ['event_date' => 'date'];
}
```

### `Event.php`
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organization_id', 'user_id', 'submission_id', 'title', 'category',
        'poster_path', 'description', 'start_at', 'end_at', 'location',
        'registration_link', 'participants', 'status',
    ];
}
```

---

## 3. Middleware Role-Based Access (`EnsureRole.php`)
Memastikan pengguna hanya dapat mengakses *dashboard* dan *resource* sesuai perannya, serta memvalidasi jika akun organisasi telah dinonaktifkan.

```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = $request->user()->role;
        if ($userRole !== $role) {
            return redirect()->route($userRole === 'kemahasiswaan' ? 'kemahasiswaan.dashboard' : 'pengurus-ukm.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        if ($userRole === 'pengurus_ukm') {
            $org = \App\Models\Organization::where('account_user_id', $request->user()->id)->first();
            if ($org && $org->status === 'Nonaktif') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['email' => 'Akun organisasi ini telah dinonaktifkan.']);
            }
        }

        return $next($request);
    }
}
```

---

## 4. Logika Alur Persetujuan & Manajemen Organisasi (`KemahasiswaanController.php`)
Mengelola seluruh validasi pengajuan (Proposal dan LPJ), penerbitan pengumuman, serta kontrol hak akses organisasi.

```php
<?php
namespace App\Http\Controllers;

use App\Models\ActivitySubmission;
use App\Models\Organization;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;

class KemahasiswaanController extends Controller
{
    // Memproses Status Pengajuan Proposal / LPJ
    public function review(Request $request, $id)
    {
        $data = $request->validate([
            'action' => 'required|string|in:diajukan,review,revisi,approved,rejected',
            'revision_note' => 'nullable|string|max:2000',
        ]);

        $submission = ActivitySubmission::findOrFail($id);
        $submission->status = $data['action'];

        if ($data['action'] === 'revisi' && !empty($data['revision_note'])) {
            $submission->revision_note = $data['revision_note'];
        }
        $submission->save();

        // Sinkronisasi Status Kegiatan Publik
        if ($submission->event_id) {
            $event = \App\Models\Event::find($submission->event_id);
            if ($event) {
                $event->status = ($data['action'] === 'approved') ? 'upcoming' : 'draft';
                $event->save();
            }
        }

        // Pengiriman Notifikasi Ke Organisasi
        $submissionOwner = User::find($submission->user_id);
        if ($submissionOwner) {
            $submissionOwner->notify(new GeneralNotification(
                "Status Pengajuan: " . ucfirst($data['action']),
                "Pengajuan \"{$submission->title}\" telah " . ucfirst($data['action']),
                'pengajuan_kegiatan',
                'info',
                route('pengurus-ukm.submissions.index')
            ));
        }

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    // Mengaktifkan atau Menonaktifkan Akses Organisasi UKM
    public function toggleStatus(Request $request, $id)
    {
        $org = Organization::findOrFail($id);
        if ($org->kategori === 'BEM') {
            abort(403, 'Akun BEM tidak dapat dinonaktifkan.');
        }

        $org->status = $org->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $org->save();

        if ($org->status === 'Nonaktif') {
            $recipients = array_filter([$org->advisor_email, $org->account_email]);
            $messageBody = "Yth. Pembina & Pengurus {$org->name},\nAkun organisasi Anda telah dinonaktifkan oleh Kemahasiswaan.";
            
            \Illuminate\Support\Facades\Mail::raw($messageBody, function ($message) use ($recipients) {
                $message->to($recipients)->subject("Notifikasi: Akun Organisasi Dinonaktifkan");
            });
        }

        return response()->json(['message' => 'Status organisasi diubah menjadi ' . $org->status]);
    }
}
```

---

## 5. Logika Pengajuan UKM & Validasi Blokir Kalender (`PengurusUkmController.php`)
Mengelola pembuatan *event* baru, pengajuan revisi proposal/LPJ, dan integrasi terhadap filter masa blokir kegiatan dari kalender akademik.

```php
<?php
namespace App\Http\Controllers;

use App\Models\ActivitySubmission;
use App\Models\Event as OrgEvent;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class PengurusUkmController extends Controller
{
    public function storeSubmission(Request $request)
    {
        $kind = $request->input('kind', 'proposal');

        if ($kind === 'proposal') {
            $request->validate([
                'title' => 'required|string|max:255',
                'event_date' => 'required|date|after_or_equal:today',
                'proposal_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);

            // Deteksi Overlap dengan Masa Blokir (Extracurricular Blocked)
            $blocked = CalendarEvent::where('extracurricular_blocked', true)
                ->where('start_date', '<=', $request->event_date)
                ->where('end_date', '>=', $request->event_date)
                ->first();

            if ($blocked) {
                return back()->withErrors([
                    'event_date' => "Tanggal {$request->event_date} bertepatan dengan masa blokir: \"{$blocked->title}\".",
                ]);
            }

            ActivitySubmission::create([
                'organization_id' => auth()->user()->organization_id,
                'user_id' => auth()->id(),
                'title' => $request->title,
                'event_date' => $request->event_date,
                'proposal_path' => $request->hasFile('proposal_file') ? $request->file('proposal_file')->store('proposals', 'public') : null,
                'kind' => 'proposal',
                'status' => 'diajukan',
            ]);

            return redirect()->back()->with('success', 'Pengajuan berhasil dikirim.');
        }
    }

    // Manajemen Berita Otomatis Pasca Kegiatan
    public function createNewsFromEvent(Request $request, $id)
    {
        $event = OrgEvent::findOrFail($id);
        if ($event->organization_id !== auth()->user()->organization_id) abort(403);

        \App\Models\Announcement::create([
            'organization_id' => $event->organization_id,
            'title' => 'Berita: ' . $event->title,
            'content' => "Telah terlaksana kegiatan " . $event->title . ".\n\n" . $event->description,
            'category' => 'Berita',
            'target' => 'Semua Mahasiswa',
            'status' => 'draft',
        ]);

        return redirect()->route('pengurus-ukm.announcements.index')->with('success', 'Berita berhasil di-draft.');
    }
}
```

---

## 6. Fitur Lost & Found Oleh Mahasiswa & BEM (`LostFoundController.php`)
Sistem pelaporan barang hilang / temuan yang diintegrasikan langsung antara pelapor dan pengurus.

```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LostItem;

class LostFoundController extends Controller
{
    // Pelaporan Oleh Mahasiswa
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lost,found',
            'title' => 'required|string|max:255',
            'date' => 'required|date|before_or_equal:today',
            'contact_phone' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'image' => 'required|image|max:5120',
        ]);

        LostItem::create([
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'image_path' => $request->file('image')->store('lost-found', 'public'),
            'status' => 'pending', // Perlu verifikasi BEM Universitas
        ]);

        return redirect()->route('lost-found.index')->with('success', 'Laporan berhasil dikirim dan menunggu review BEM.');
    }
}
```

---

*Catatan: Dokumen ini telah diringkas untuk menampilkan representasi substansial dari struktur data, routing, middleware, dan pengkondisian proses bisnis. Kode repositori seutuhnya mencakup lebih dari 15.000 baris kode yang memuat implementasi Views/Blade, Vanilla CSS, Migrations Database, serta PDF Extractor Services.*
