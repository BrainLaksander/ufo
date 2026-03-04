@extends('layouts.pengurus')

@section('title', 'Dashboard Pengurus - Advanced')

@section('content')
<div class="page-header mb-4">
    <h1>Dashboard Pengurus Organisasi</h1>
    <p class="page-subtitle">Kelola organisasi dengan sistem manajemen lengkap</p>
</div>

<!-- ===== STAT CARDS (6 CARDS) ===== -->
<div class="row g-3 mb-4">
    <!-- CARD 1: Status Profil -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                <div class="stat-card-icon">📋</div>
                <div style="font-size: 12px; font-weight: 600; color: var(--text-secondary);">PROFIL</div>
            </div>
            <div class="stat-card-value">{{ $profileCompletion }}%</div>
            <div class="stat-card-label">Status Profil Organisasi</div>
            <div style="margin-top: 10px;">
                <div style="background: #e0e0e0; height: 6px; border-radius: 3px; margin-bottom: 10px; overflow: hidden;">
                    <div style="background: var(--primary); height: 100%; width: {{ $profileCompletion }}%; transition: width 0.3s;"></div>
                </div>
                <span class="badge-org @if($profileStatus == 'lengkap') success @else warning @endif">
                    @if($profileStatus == 'lengkap') ✓ Lengkap @else ⚠️ Belum Lengkap @endif
                </span>
            </div>
            <a href="/portal/pengurus/settings" class="btn-primary-org" style="width: 100%; text-align: center; margin-top: 10px; text-decoration: none; display: block;">Lengkapi Profil →</a>
        </div>
    </div>

    <!-- CARD 2: Anggota Aktif -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-card-icon">👥</div>
            <div class="stat-card-value">{{ $activeMembers }}</div>
            <div class="stat-card-label">Anggota Aktif</div>
            <a href="/portal/pengurus/members" class="btn-secondary-org" style="width: 100%; text-align: center; margin-top: 15px; text-decoration: none; display: block;">Kelola Anggota →</a>
        </div>
    </div>

    <!-- CARD 3: Event Aktif -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-card-icon">📅</div>
            <div class="stat-card-value">{{ $activeEvents }}</div>
            <div class="stat-card-label">Event Aktif & Berjalan</div>
            <a href="/portal/pengurus/events" class="btn-secondary-org" style="width: 100%; text-align: center; margin-top: 15px; text-decoration: none; display: block;">Lihat Event →</a>
        </div>
    </div>

    <!-- CARD 4: Event Selesai -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-card-icon">✓</div>
            <div class="stat-card-value">{{ $completedEvents }}</div>
            <div class="stat-card-label">Event Selesai</div>
            <button class="btn-primary-org" style="width: 100%; margin-top: 15px;">📤 Upload Laporan</button>
        </div>
    </div>

    <!-- CARD 5: Pengajuan Disetujui -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-card-icon">✅</div>
            <div class="stat-card-value">{{ $approvedSubmissions }}</div>
            <div class="stat-card-label">Pengajuan Disetujui</div>
            <a href="/portal/pengurus/proposals" class="btn-secondary-org" style="width: 100%; text-align: center; margin-top: 15px; text-decoration: none; display: block;">Lihat Pengajuan →</a>
        </div>
    </div>

    <!-- CARD 6: Laporan Terkirim -->
    <div class="col-md-6 col-lg-4">
        <div class="stat-card">
            <div class="stat-card-icon">📝</div>
            <div class="stat-card-value">{{ $submittedReports }}</div>
            <div class="stat-card-label">Laporan Terkirim</div>
            <p style="font-size: 12px; color: var(--text-secondary); margin-top: 10px;">Menunggu review / Diterima</p>
        </div>
    </div>
</div>

<!-- ===== MAIN CONTENT GRID ===== -->
<div class="row g-3">
    <!-- LEFT: ACTIVITIES + SUBMISSIONS -->
    <div class="col-lg-8">
        <!-- RECENT ACTIVITIES -->
        <div class="bg-white rounded-3 shadow-sm border p-4 mb-3" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;">🔔 Aktivitas Terbaru</h4>
            <div style="max-height: 300px; overflow-y: auto;">
                @forelse($activities as $activity)
                <div style="border-left: 4px solid var(--primary); padding-left: 15px; margin-bottom: 15px;">
                    <div style="font-weight: 600; color: var(--primary); font-size: 13px;">{{ $activity->description }}</div>
                    <div style="font-size: 12px; color: var(--text-secondary); margin-top: 3px;">
                        {{ $activity->created_at->diffForHumans() }}
                        @if($activity->member)
                            | Oleh: {{ $activity->member->name }}
                        @endif
                    </div>
                </div>
                @empty
                <p style="color: var(--text-secondary); text-align: center; padding: 20px;">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>

        <!-- RECENT SUBMISSIONS -->
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;">📋 Pengajuan Terbaru</h4>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead style="background: var(--primary); color: white;">
                        <tr>
                            <th style="border: none; padding: 10px;">Judul</th>
                            <th style="border: none; padding: 10px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSubmissions as $sub)
                        <tr style="border-bottom: 1px solid #f0f0f0;">
                            <td style="padding: 10px; font-size: 13px;">{{ $sub->title }}</td>
                            <td style="padding: 10px;">
                                @if($sub->status == 'approved')
                                    <span class="badge-org success">✓ Disetujui</span>
                                @elseif($sub->status == 'revision')
                                    <span class="badge-org warning">🔧 Revisi</span>
                                @else
                                    <span class="badge-org info">📨 {{ $sub->status }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="2" style="padding: 15px; text-align: center; color: var(--text-secondary);">Belum ada pengajuan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RIGHT: PENDING TASKS + RECENT EVENTS -->
    <div class="col-lg-4">
        <!-- PENDING TASKS -->
        <div class="bg-white rounded-3 shadow-sm border p-4 mb-3" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;">📌 Tugas Pending</h4>
            <div style="max-height: 400px; overflow-y: auto;">
                @forelse($pendingTasks as $task)
                <div style="padding: 12px; border: 1px solid #f0f0f0; border-radius: 8px; margin-bottom: 10px; display: flex; gap: 10px;">
                    <input type="checkbox" class="task-checkbox" data-task-id="{{ $task->id }}" style="cursor: pointer;">
                    <div style="flex: 1; font-size: 13px;">
                        <div style="font-weight: 600; color: var(--primary); margin-bottom: 3px;">{{ $task->title }}</div>
                        @if($task->deadline)
                            <div style="font-size: 11px; color: var(--text-secondary);">⏰ {{ $task->deadline->format('d M Y') }}</div>
                        @endif
                        <span class="badge-org @if($task->priority == 'urgent') danger @elseif($task->priority == 'normal') warning @else success @endif" style="font-size: 10px;">{{ $task->priority }}</span>
                    </div>
                </div>
                @empty
                <p style="color: var(--text-secondary); text-align: center; padding: 20px;">Tidak ada tugas pending</p>
                @endforelse

                @if(count($autoTasks) > 0)
                <div style="margin-top: 15px; padding-top: 15px; border-top: 2px solid #f0f0f0;">
                    <h6 style="font-weight: 700; color: var(--danger); font-size: 12px; margin-bottom: 10px;">⚠️ TUGAS OTOMATIS SISTEM</h6>
                    @foreach($autoTasks as $task)
                    <div style="padding: 12px; border: 1px solid rgba(204, 0, 0, 0.2); border-radius: 8px; margin-bottom: 10px; background: rgba(204, 0, 0, 0.05);">
                        <div style="font-weight: 600; color: var(--danger); margin-bottom: 3px; font-size: 13px;">{{ $task['title'] }}</div>
                        <div style="font-size: 11px; color: var(--text-secondary);">{{ $task['description'] }}</div>
                        @if($task['deadline'])
                            <div style="font-size: 10px; color: var(--danger); margin-top: 5px;">⏰ Deadline: {{ $task['deadline'] }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- UPCOMING EVENTS -->
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0;">
            <h4 class="text-primary mb-3" style="font-weight: 700;">📅 Event Terdekat</h4>
            <div style="max-height: 250px; overflow-y: auto;">
                @forelse($recentEvents as $event)
                <div style="padding: 12px; border: 1px solid #f0f0f0; border-radius: 8px; margin-bottom: 10px;">
                    <div style="font-weight: 600; color: var(--primary); font-size: 13px; margin-bottom: 3px;">{{ $event->name }}</div>
                    <div style="font-size: 11px; color: var(--text-secondary);">📅 {{ $event->start_date->format('d M Y') }}</div>
                    <div style="font-size: 11px; color: var(--text-secondary);">👥 {{ $event->participants_count }}/{{ $event->quota }} peserta</div>
                    <span class="badge-org @if($event->status == 'berjalan') warning @else success @endif" style="font-size: 10px; margin-top: 5px; display: inline-block;">{{ $event->status }}</span>
                </div>
                @empty
                <p style="color: var(--text-secondary); text-align: center; padding: 20px;">Belum ada event</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.querySelectorAll('.task-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const taskId = this.dataset.taskId;
        const status = this.checked ? 'selesai' : 'pending';
        
        fetch(`/portal/pengurus/task-update/${taskId}/${status}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                this.closest('div[style*="padding: 12px"]').style.opacity = this.checked ? '0.6' : '1';
                console.log('Task updated:', data.message);
            }
        })
        .catch(err => console.error('Error:', err));
    });
});
</script>
