# 📚 DOKUMENTASI DASHBOARD PENGURUS ORGANISASI

## 🎯 OVERVIEW

Dashboard Pengurus Organisasi adalah sistem manajemen lengkap untuk pengurus organisasi mahasiswa dengan 6 stat cards, sistem tugas otomatis, log aktivitas, dan integrasi ke semua fitur organisasi.

---

## 🧩 BAGIAN 1: STAT CARDS (6 KOMPONEN)

### CARD 1️⃣: Status Profil Organisasi

**Fungsi**: Menampilkan persentase kelengkapan profil organisasi

**Kriteria Penilaian** (8 komponen):

- ✓ Logo upload (ada/tidak ada)
- ✓ Banner upload (ada/tidak ada)
- ✓ Deskripsi lengkap (>20 karakter)
- ✓ Visi terisi
- ✓ Misi terisi
- ✓ Email contact tersedia
- ✓ Nomor telepon tersedia
- ✓ Minimal 3 anggota terdaftar

**Status Profil**:

- ≥ 75% = "Lengkap" ✅ (warna hijau)
- < 75% = "Belum Lengkap" ⚠️ (warna kuning)

**Query Laravel**:

```php
$org->calculateProfileCompletion(); // Hitung otomatis
$profileCompletion = $org->profile_completion_percentage; // 0-100
```

**Aksi User**:

- Klik "Lengkapi Profil" → Redirect ke halaman `/portal/pengurus/settings`
- Progress bar visual menampilkan persentase

**Database**:

```sql
Tabel: organizations
- profile_completion_percentage (integer)
- profile_status (enum: 'lengkap', 'belum_lengkap')
```

---

### CARD 2️⃣: Anggota Aktif

**Fungsi**: Menampilkan jumlah anggota dengan status 'aktif'

**Query**:

```php
$activeMembers = $org->members()
    ->where('status', 'aktif')
    ->count();
```

**Database**:

```sql
Tabel: members
- organization_id (FK)
- status (enum: 'aktif', 'nonaktif', 'cuti')
```

**Aksi**:

- Klik card → `/portal/pengurus/members`
- List anggota aktif dengan opsi ubah status

---

### CARD 3️⃣: Event Aktif & Berjalan

**Fungsi**: Menampilkan jumlah event dengan status 'approved' atau 'berjalan'

**Query**:

```php
$activeEvents = $org->events()
    ->whereIn('status', ['approved', 'berjalan'])
    ->count();
```

**Status Event**:

- `draft` = Baru dibuat, belum disetujui
- `approved` = Disetujui, siap berjalan
- `berjalan` = Sedang berlangsung
- `selesai` = Event sudah selesai
- `cancelled` = Dibatalkan

**Aksi**:

- Klik card → `/portal/pengurus/events`
- Badge menampilkan status real-time

---

### CARD 4️⃣: Event Selesai

**Fungsi**: Menampilkan jumlah event dengan status 'selesai'

**Query**:

```php
$completedEvents = $org->events()
    ->where('status', 'selesai')
    ->count();
```

**Aksi**:

- Tombol "📤 Upload Laporan" → Modal untuk upload laporan kegiatan
- Sistem otomatis membuat Task jika ada event selesai tapi belum ada laporan

---

### CARD 5️⃣: Pengajuan Disetujui

**Fungsi**: Menampilkan total pengajuan/proposal yang disetujui kemahasiswaan

**Query**:

```php
$approvedSubmissions = $org->submissions()
    ->where('status', 'approved')
    ->count();
```

**Database**:

```sql
Tabel: submissions
- organization_id (FK)
- status (enum: 'draft', 'submitted', 'approved', 'rejected', 'revision')
- type (enum: 'proposal', 'laporan', 'dokumen')
```

**Aksi**:

- Klik card → `/portal/pengurus/proposals`
- Timeline approval ditampilkan

---

### CARD 6️⃣: Laporan Terkirim

**Fungsi**: Menampilkan total laporan dengan status kirim/review/diterima

**Query**:

```php
$submittedReports = $org->reports()
    ->whereIn('status', ['submitted', 'pending_review', 'accepted'])
    ->count();
```

**Database**:

```sql
Tabel: reports
- organization_id (FK)
- event_id (FK)
- status (enum: 'draft', 'submitted', 'pending_review', 'accepted', 'revision')
```

**Status Report**:

- `submitted` = 📨 Baru dikirim
- `pending_review` = ⏳ Menunggu review
- `accepted` = ✅ Diterima
- `revision` = 🔧 Perlu revisi

---

## 🔔 BAGIAN 2: RECENT ACTIVITIES (LOG SISTEM)

**Tabel**: `activity_logs`

**Field**:

```sql
- id
- organization_id (FK)
- member_id (FK) - siapa yang melakukan
- action (string)
- description (text)
- model_type (string) - Event, Submission, Report, dll
- model_id (bigint) - ID dari model yang terkait
- changes (json) - perubahan yang dilakukan
- created_at
- updated_at
```

**Event yang Dicatat**:

- ✏️ Event dipublikasikan
- 👤 Anggota mendaftar
- 📢 Pengumuman disetujui
- ✅ Proposal disetujui
- 📝 Laporan diterima
- 🔧 Revision diminta
- 🎯 Task diselesaikan

**Contoh Log**:

```
"Event 'Workshop Coding' dipublikasikan oleh Budi Santoso" → 2 jam lalu
"Proposal 'Workshop 2026' disetujui oleh Kemahasiswaan" → 5 jam lalu
"Laporan Event 'Diskusi AI' diterima" → 1 hari lalu
```

**Query**:

```php
$activities = ActivityLog::where('organization_id', $org->id)
    ->with('member')
    ->latest()
    ->limit(10)
    ->get();

// Tampilkan dengan diffForHumans()
echo $activity->created_at->diffForHumans(); // "2 jam lalu"
```

---

## 📌 BAGIAN 3: PENDING TASKS (TASK MANAGEMENT)

### Task Manual (Dibuat Pengurus)

Pengurus dapat membuat task dengan:

- Judul & deskripsi
- Prioritas: `urgent`, `normal`, `low`
- Deadline (opsional)
- Status: `pending`, `in_progress`, `selesai`

### Task Otomatis (Sistem)

Sistem otomatis membuat task jika:

**1. Profil Belum Lengkap** (Priority: URGENT)

```
Judul: "⚠️ Lengkapi Profil Organisasi"
Deskripsi: "Profil organisasi Anda baru 65% lengkap"
Deadline: Tidak ada (berlaku sampai 75%)
Aksi: Klik → ke halaman settings
```

**2. Event Selesai Tanpa Laporan** (Priority: NORMAL)

```
Judul: "📝 Kirim Laporan Event: [Nama Event]"
Deskripsi: "Event sudah selesai, silakan upload laporan"
Deadline: 7 hari setelah event selesai
Aksi: Upload file laporan
```

**3. Submission Ditolak (Revision)** (Priority: URGENT)

```
Judul: "🔧 Revisi Pengajuan: [Nama Proposal]"
Deskripsi: "Pengajuan memerlukan revisi. Alasan: ..."
Deadline: 3 hari dari sekarang
Aksi: Re-upload submission
```

**Database**:

```sql
Tabel: tasks
- id
- organization_id (FK)
- title (string)
- description (text)
- priority (enum: 'urgent', 'normal', 'low')
- status (enum: 'pending', 'in_progress', 'selesai')
- deadline (date)
- type (string) - 'profile', 'report', 'submission', dll
- related_id (string) - ID dari item terkait
- completed_at (timestamp)
- created_at
- updated_at
```

### UI Task

- ☑️ Checkbox untuk "Tandai Selesai"
- Color coding:
  - 🔴 Urgent = background merah muda
  - 🟡 Normal = background kuning
  - 🟢 Low = background hijau
- Auto-fetch deadline dan tampilkan ⏰
- Completed task tampil semi-transparent

### Interaksi JavaScript

```javascript
document.querySelectorAll('.task-checkbox').forEach((checkbox) => {
  checkbox.addEventListener('change', function () {
    const taskId = this.dataset.taskId;
    const status = this.checked ? 'selesai' : 'pending';

    fetch(`/portal/pengurus/task-update/${taskId}/${status}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': token },
    })
      .then((res) => res.json())
      .then((data) => {
        console.log('Task updated');
      });
  });
});
```

---

## 🗄️ BAGIAN 4: DATABASE SCHEMA

### Tabel: `organizations`

```sql
CREATE TABLE organizations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    shortname VARCHAR(50) UNIQUE NOT NULL,
    logo VARCHAR(255) NULL,
    banner VARCHAR(255) NULL,
    description TEXT NULL,
    vision TEXT NULL,
    mission TEXT NULL,
    email VARCHAR(255) NULL,
    phone VARCHAR(20) NULL,
    instagram VARCHAR(100) NULL,
    line_id VARCHAR(100) NULL,
    profile_status ENUM('lengkap', 'belum_lengkap') DEFAULT 'belum_lengkap',
    profile_completion_percentage INT DEFAULT 0,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

### Tabel: `members`

```sql
CREATE TABLE members (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    nim VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    division VARCHAR(100) NULL,
    position ENUM('ketua', 'sekretaris', 'bendahara', 'staff') DEFAULT 'staff',
    status ENUM('aktif', 'nonaktif', 'cuti') DEFAULT 'aktif',
    join_date DATE NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
);
```

### Tabel: `events`

```sql
CREATE TABLE events (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NULL,
    location VARCHAR(255) NULL,
    quota INT DEFAULT 100,
    participants_count INT DEFAULT 0,
    banner VARCHAR(255) NULL,
    status ENUM('draft','approved','berjalan','selesai','cancelled') DEFAULT 'draft',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
);
```

### Tabel: `submissions`

```sql
CREATE TABLE submissions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    file_path VARCHAR(255) NULL,
    type ENUM('proposal', 'laporan', 'dokumen') DEFAULT 'proposal',
    status ENUM('draft','submitted','approved','rejected','revision') DEFAULT 'draft',
    rejection_reason TEXT NULL,
    notes TEXT NULL,
    submitted_at TIMESTAMP NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
);
```

### Tabel: `reports`

```sql
CREATE TABLE reports (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT NOT NULL,
    event_id BIGINT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    file_path VARCHAR(255) NULL,
    findings TEXT NULL,
    status ENUM('draft','submitted','pending_review','accepted','revision') DEFAULT 'draft',
    submitted_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    review_notes TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE SET NULL
);
```

### Tabel: `tasks`

```sql
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    priority ENUM('urgent', 'normal', 'low') DEFAULT 'normal',
    status ENUM('pending', 'in_progress', 'selesai') DEFAULT 'pending',
    deadline DATE NULL,
    type VARCHAR(100) NULL,
    related_id VARCHAR(255) NULL,
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
);
```

### Tabel: `activity_logs`

```sql
CREATE TABLE activity_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    organization_id BIGINT NOT NULL,
    member_id BIGINT NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    model_type VARCHAR(255) NULL,
    model_id BIGINT NULL,
    changes JSON NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);
```

---

## 🎮 BAGIAN 5: CONTROLLER & ROUTES

### DashboardOrganisasiController

**File**: `app/Http/Controllers/DashboardOrganisasiController.php`

**Method: index()**

```php
public function index(): View {
    // Fetch organization (mock: org_id = 1)
    $org = Organization::find(1);

    // Calculate stats
    $profileCompletion = $org->profile_completion_percentage;
    $activeMembers = $org->getActiveMembers();
    $activeEvents = $org->getActiveEvents();
    // ... dll

    // Fetch activities, tasks, events, submissions, reports
    $activities = ActivityLog::...->latest()->limit(10)->get();
    $pendingTasks = Task::...->limit(8)->get();
    $autoTasks = $this->generateAutoTasks($org);

    return view('pages.pengurus.dashboard-advanced', compact(...));
}
```

**Method: generateAutoTasks()**

```php
private function generateAutoTasks(Organization $org) {
    $autoTasks = [];

    // Profile task
    if($org->profile_completion_percentage < 75) {
        $autoTasks[] = [...];
    }

    // Event report tasks
    $eventsWithoutReports = Event::...->get();
    foreach($eventsWithoutReports as $event) {
        $autoTasks[] = [...];
    }

    // Revision tasks
    $rejectedSubmissions = Submission::where('status', 'revision')->get();
    foreach($rejectedSubmissions as $sub) {
        $autoTasks[] = [...];
    }

    return $autoTasks;
}
```

**Method: updateTaskStatus()**

```php
public function updateTaskStatus($taskId, $status) {
    $task = Task::findOrFail($taskId);
    $task->status = $status;
    if($status === 'selesai') {
        $task->completed_at = Carbon::now();
    }
    $task->save();

    // Log activity
    ActivityLog::create([...]);

    return response()->json(['success' => true]);
}
```

### Routes

**File**: `routes/web.php`

```php
use App\Http\Controllers\DashboardOrganisasiController;

Route::prefix('/portal/pengurus')->group(function () {
    // Dashboard (Enhanced)
    Route::get('/', [DashboardOrganisasiController::class, 'index'])
        ->name('portal.pengurus.dashboard');

    // Task update (AJAX)
    Route::post('/task-update/{taskId}/{status}',
        [DashboardOrganisasiController::class, 'updateTaskStatus']);

    // Other routes (events, announcements, dll)
    Route::get('/events', [PengurusController::class, 'events']);
    Route::get('/announcements', [PengurusController::class, 'announcements']);
    // ... dll
});
```

---

## 🎨 BAGIAN 6: UI/UX (BOOTSTRAP STYLE)

### Styling Guidelines

- **Card style**: `.stat-card`, rounded corners 12px, shadow ringan
- **Button**: `.btn-primary-org` (ungu), `.btn-secondary-org` (outline)
- **Badge**: `.badge-org` dengan color variants (success, warning, danger, info)
- **Table**: Responsive, header berwarna primary
- **Color palette**:
  - Primary: #663399 (ungu)
  - Accent: #FFCC00 (kuning)
  - Success: #00AA00 (hijau)
  - Danger: #CC0000 (merah)

### Layout Components

**1. Stat Card Template**

```html
<div class="stat-card">
  <div class="stat-card-icon">📋</div>
  <div class="stat-card-value">65%</div>
  <div class="stat-card-label">Status Profil</div>
  <div style="progress bar..."></div>
  <a href="..." class="btn-primary-org">Lengkapi Profil →</a>
</div>
```

**2. Activity Log Item**

```html
<div style="border-left: 4px solid primary; padding-left: 15px;">
  <div style="font-weight: 600;">Deskripsi aktivitas</div>
  <div style="font-size: 12px; color: secondary;">
    2 jam lalu | Oleh: Nama Member
  </div>
</div>
```

**3. Task Item**

```html
<div style="display: flex; gap: 10px;">
  <input type="checkbox" class="task-checkbox" data-task-id="1" />
  <div>
    <div style="font-weight: 600;">Judul Task</div>
    <div style="font-size: 11px;">⏰ Deadline: 5 Jan 2026</div>
    <span class="badge-org urgent">URGENT</span>
  </div>
</div>
```

### Responsive Grid

- Desktop (lg): 6 stat cards dalam 3 kolom
- Tablet (md): 6 stat cards dalam 2 kolom
- Mobile (sm): 6 stat cards dalam 1 kolom

---

## 💻 CONTOH IMPLEMENTASI ALUR

### Alur 1: Pengguna Buka Dashboard

1. Pengurus login → redirect ke `/portal/pengurus`
2. Controller fetch Organization, Members, Events, Tasks, Activities
3. Generate auto-tasks dari kondisi (profile, laporan, revisi)
4. Render view dengan 6 stat cards + activities + tasks
5. User lihat dashboard dengan data real-time

### Alur 2: Pengguna Mark Task Selesai

1. User klik checkbox pada task
2. JavaScript kirim POST request ke `/portal/pengurus/task-update/{id}/selesai`
3. Controller update task status & completed_at
4. Catat activity log: "Task '...' diselesaikan"
5. Return JSON response {success: true}
6. Frontend update UI: task jadi semi-transparent

### Alur 3: Event Selesai, System Generate Task

1. Admin/staff ubah event status menjadi 'selesai'
2. Trigger (bisa via event listener atau manual check)
3. System query: Events dengan status 'selesai' tapi tanpa report
4. Create Task otomatis dengan priority 'normal', deadline 7 hari
5. Activity log: "Event selesai, task laporan dibuat"
6. Task muncul di dashboard pengurus dengan icon 📝

---

## 📊 CONTOH DATA DASHBOARD

```
📊 STAT CARDS:
- Profil: 65% Belum Lengkap (progress bar visual)
- Anggota: 45 Aktif
- Event Aktif: 3
- Event Selesai: 2
- Pengajuan Disetujui: 4
- Laporan Terkirim: 3

🔔 RECENT ACTIVITIES (10 items):
1. "Event 'Workshop Coding' dipublikasikan" - 2 jam lalu
2. "Proposal 'Workshop 2026' disetujui kemahasiswaan" - 5 jam lalu
3. "Laporan 'Diskusi AI' diterima" - 1 hari lalu
...

📌 PENDING TASKS:
[URGENT] ⚠️ Lengkapi Profil Organisasi
         Profil baru 65% lengkap
         [Lengkapi Profil] button

[NORMAL] 📝 Kirim Laporan Event: Workshop Coding
         ⏰ Deadline: 5 Jan 2026

[URGENT] 🔧 Revisi Pengajuan: Proposal Q1
         Alasan: Anggaran perlu detail lebih
         ⏰ Deadline: 31 Jan 2026

📅 EVENT TERDEKAT:
- Workshop Web Dev (15 Feb 2026) - 25/40 peserta
- Diskusi AI (20 Feb 2026) - 18/100 peserta
```

---

## 🔐 VALIDASI & ERROR HANDLING

### Validasi Input

- Task title: required, min 5 char, max 255
- Task deadline: valid date, >= hari ini
- Profile fields: format email, phone pattern

### Error Handling

- Profile completion < 0 atau > 100: set default 0
- Task tidak ditemukan: return 404
- CSRF token mismatch: return 419
- Unauthorized access: return 403

### Success Notifications

```javascript
// Pada checkbox change
-'✓ Tugas berhasil diselesaikan' -
  // Pada form submit
  '✓ Data berhasil disimpan';
```

---

## 📚 REFERENSI MODEL

### Organization Model

```php
public function getActiveMembers() { ... }
public function getActiveEvents() { ... }
public function getApprovedSubmissions() { ... }
public function calculateProfileCompletion() { ... }
```

### ActivityLog Model

```php
protected $fillable = ['organization_id', 'member_id', 'action', 'description', 'model_type', 'model_id', 'changes'];
protected $casts = ['changes' => 'json'];
```

---

**END OF DOCUMENTATION**

Tanggal: 30 Jan 2026
Status: Production Ready
Version: 1.0
