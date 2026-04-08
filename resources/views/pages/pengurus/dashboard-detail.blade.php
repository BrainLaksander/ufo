@extends('layouts.pengurus')

@section('title', 'Dashboard Lengkap - Pengurus Organisasi')

@section('content')
<div class="page-header mb-5">
    <h1> Dashboard Organisasi</h1>
    <p class="page-subtitle">Kelola organisasi dengan informasi real-time dan insights mendalam</p>
</div>

<!-- HEALTH INDICATOR -->
<div class="bg-white rounded-3 shadow-sm border p-4 mb-4" style="border: 2px solid #f0f0f0;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h5 class="text-primary mb-2" style="font-weight: 700;"> Kesehatan Organisasi</h5>
            <p class="text-muted mb-0">Skor berdasarkan profil, anggota, pengajuan, dan task completion</p>
        </div>
        <div class="text-center">
            <div style="font-size: 48px; font-weight: 700; color: var(--primary);">
                {{ $insights['health_status'] }}%
            </div>
            <small class="text-muted">Health Score</small>
        </div>
    </div>
    <div class="progress mt-3" style="height: 10px;">
        <div class="progress-bar" role="progressbar" style="width: {{ $insights['health_status'] }}%; background: var(--primary);" aria-valuenow="{{ $insights['health_status'] }}" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
</div>

<!-- ========== 6 STAT CARDS ========== -->
<div class="row g-3 mb-4">
    <!-- 1. STATUS PROFIL -->
    <div class="col-md-6 col-lg-4">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(102,51,153,0.15)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-muted mb-1">Status Profil</h6>
                    <h3 class="text-primary mb-0" style="font-weight: 700;">{{ $profileStatus['completion_percentage'] }}%</h3>
                </div>
                <span style="font-size: 32px;"><i class="bi bi-person-badge-fill"></i></span>
            </div>
            <div class="progress mb-3" style="height: 6px;">
                <div class="progress-bar" style="width: {{ $profileStatus['completion_percentage'] }}%; background: var(--primary);" role="progressbar"></div>
            </div>
            @if($profileStatus['is_complete'])
                <span class="badge-org success"> Lengkap</span>
            @else
                <span class="badge-org warning"> Belum Lengkap</span>
                <ul class="small text-muted mt-2 mb-0">
                    @foreach($profileStatus['missing_items'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            @endif
            <a href="{{ route('portal.pengurus.settings') }}" class="btn-primary-org mt-3" style="display: inline-flex; font-size: 12px;">Lengkapi Profil →</a>
        </div>
    </div>

    <!-- 2. ANGGOTA AKTIF -->
    <div class="col-md-6 col-lg-4">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(102,51,153,0.15)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-muted mb-1">Anggota Aktif</h6>
                    <h3 class="text-primary mb-0" style="font-weight: 700;">{{ $activeMembers['total'] }}</h3>
                </div>
                <span style="font-size: 32px;"><i class="bi bi-people-fill"></i></span>
            </div>
            <div class="small text-muted">
                @foreach($activeMembers['by_position'] as $pos)
                    <div>{{ ucfirst($pos->position) }}: <strong>{{ $pos->count }}</strong></div>
                @endforeach
            </div>
            <a href="{{ route('portal.pengurus.events') }}" class="btn-primary-org mt-3" style="display: inline-flex; font-size: 12px;">Kelola Anggota →</a>
        </div>
    </div>

    <!-- 3. EVENT AKTIF -->
    <div class="col-md-6 col-lg-4">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(102,51,153,0.15)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-muted mb-1">Event Aktif</h6>
                    <h3 class="text-primary mb-0" style="font-weight: 700;">{{ $activeEvents['total'] }}</h3>
                </div>
                <span style="font-size: 32px;"><i class="bi bi-calendar-event-fill"></i></span>
            </div>
            <div class="small text-muted">
                <div>Sedang Berlangsung: <strong>{{ $activeEvents['ongoing'] }}</strong></div>
                <div>Akan Datang: <strong>{{ count($activeEvents['upcoming']) }}</strong></div>
            </div>
            <a href="{{ route('portal.pengurus.events') }}" class="btn-primary-org mt-3" style="display: inline-flex; font-size: 12px;">Lihat Event →</a>
        </div>
    </div>

    <!-- 4. EVENT SELESAI -->
    <div class="col-md-6 col-lg-4">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(102,51,153,0.15)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-muted mb-1">Event Selesai</h6>
                    <h3 class="text-primary mb-0" style="font-weight: 700;">{{ $completedEvents['total'] }}</h3>
                </div>
                <span style="font-size: 32px;"><i class="bi bi-calendar2-check-fill"></i></span>
            </div>
            <div class="small text-muted">
                <div>Menunggu Laporan: <strong>{{ $completedEvents['pending_report'] }}</strong></div>
            </div>
            @if($completedEvents['pending_report'] > 0)
                <button class="btn-primary-org mt-3" style="display: inline-flex; font-size: 12px;"> Upload Laporan →</button>
            @endif
        </div>
    </div>

    <!-- 5. PENGAJUAN DISETUJUI -->
    <div class="col-md-6 col-lg-4">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(102,51,153,0.15)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-muted mb-1">Pengajuan Disetujui</h6>
                    <h3 class="text-primary mb-0" style="font-weight: 700;">{{ $approvedSubmissions['total'] }}</h3>
                </div>
                <span style="font-size: 32px;"><i class="bi bi-file-earmark-check-fill"></i></span>
            </div>
            <div class="small text-muted">
                @foreach($approvedSubmissions['by_type'] as $type)
                    <div>{{ ucfirst($type->type) }}: <strong>{{ $type->count }}</strong></div>
                @endforeach
            </div>
            <a href="{{ route('portal.pengurus.submissions') }}" class="btn-primary-org mt-3" style="display: inline-flex; font-size: 12px;">Lihat Detail →</a>
        </div>
    </div>

    <!-- 6. LAPORAN TERKIRIM -->
    <div class="col-md-6 col-lg-4">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.boxShadow='0 8px 20px rgba(102,51,153,0.15)'" onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.05)'">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="text-muted mb-1">Laporan Terkirim</h6>
                    <h3 class="text-primary mb-0" style="font-weight: 700;">{{ $submittedReports['total'] }}</h3>
                </div>
                <span style="font-size: 32px;"><i class="bi bi-send-check-fill"></i></span>
            </div>
            <div class="small text-muted">
                <div>Menunggu: <strong>{{ $submittedReports['by_status']['pending'] }}</strong></div>
                <div>Diterima: <strong>{{ $submittedReports['by_status']['accepted'] }}</strong></div>
                <div>Revisi: <strong>{{ $submittedReports['by_status']['revision'] }}</strong></div>
            </div>
            <a href="{{ route('portal.pengurus.reports') }}" class="btn-primary-org mt-3" style="display: inline-flex; font-size: 12px;">Lihat Laporan →</a>
        </div>
    </div>
</div>

<!-- URGENT ATTENTION SECTION -->
@if(count($insights['urgent_attention']) > 0)
<div class="bg-white rounded-3 shadow-sm border p-4 mb-4" style="border: 2px solid #ffcccc; background: #fffaf5;">
    <h5 class="text-danger mb-3" style="font-weight: 700;"> Perlu Perhatian Urgent</h5>
    <div class="d-flex flex-column gap-2">
        @foreach($insights['urgent_attention'] as $item)
            <a href="{{ $item['link'] }}" class="btn btn-light text-start p-3 rounded-2" style="border: 1px solid #ffcccc; background: white; transition: all 0.2s;" onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='white'">
                <div class="text-danger" style="font-weight: 600;">{{ $item['message'] }}</div>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- TWO-COLUMN LAYOUT -->
<div class="row g-3">
    <!-- COLUMN 1: RECENT ACTIVITIES -->
    <div class="col-lg-6">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0;">
            <h5 class="text-primary mb-4" style="font-weight: 700;"> Aktivitas Terbaru</h5>
            <div class="d-flex flex-column gap-3">
                @forelse($recentActivities as $log)
                    <div class="p-3 rounded-2" style="background: #f9f9f9; border-left: 4px solid var(--primary);">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 24px;"><i class="bi {{ $log->getActivityIcon() }}"></i></span>
                            <div style="flex: 1;">
                                <div style="font-weight: 600; color: var(--primary); font-size: 13px;">{{ $log->getActivityLabel() }}</div>
                                <div style="font-size: 12px; color: var(--text-secondary);">{{ $log->description }}</div>
                                <div style="font-size: 11px; color: #999; margin-top: 5px;">{{ $log->getRelativeTime() }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- COLUMN 2: PENDING TASKS -->
    <div class="col-lg-6">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #f0f0f0;">
            <h5 class="text-primary mb-4" style="font-weight: 700;"> Tugas Pending</h5>
            <div class="d-flex flex-column gap-2">
                @forelse($pendingTasks['all'] as $task)
                    <div class="p-3 rounded-2 task-item" style="background: #f9f9f9; border-left: 4px solid #{{ $task->getPriorityColor() === 'danger' ? 'cc0000' : ($task->getPriorityColor() === 'warning' ? 'ffa500' : 'ffc

00') }}; display: flex; justify-content: space-between; align-items: center;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: var(--primary); font-size: 13px;">{{ $task->title }}</div>
                            <div style="font-size: 12px; color: var(--text-secondary); margin-top: 3px;">
                                @if($task->deadline)
                                    Deadline: {{ $task->deadline->format('d M Y') }}
                                    @if($task->isOverdue())
                                        <span class="badge-org danger">OVERDUE</span>
                                    @elseif($task->daysUntilDeadline() === 0)
                                        <span class="badge-org danger">HARI INI</span>
                                    @elseif($task->daysUntilDeadline() <= 3)
                                        <span class="badge-org warning">SEGERA</span>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <input type="checkbox" class="task-checkbox" data-task-id="{{ $task->id }}" style="width: 20px; height: 20px; cursor: pointer;">
                    </div>
                @empty
                    <p class="text-muted text-center py-3"> Tidak ada tugas pending</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- UPCOMING DEADLINES -->
@if(count($insights['upcoming_deadlines']) > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="bg-white rounded-3 shadow-sm border p-4" style="border: 2px solid #fff3cd; background: #fffbf0;">
            <h5 class="text-warning mb-3" style="font-weight: 700;"> Deadline Minggu Depan</h5>
            <div class="row g-2">
                @foreach($insights['upcoming_deadlines']->take(4) as $deadline)
                    <div class="col-md-6">
                        <div class="p-3 rounded-2" style="background: white; border: 1px solid #ffe0b2; display: flex; justify-content: space-between;">
                            <div>
                                <div style="font-weight: 600; color: var(--primary); font-size: 13px;">{{ $deadline->title }}</div>
                                <small class="text-muted">{{ $deadline->deadline->diffForHumans() }}</small>
                            </div>
                            <span style="font-size: 20px;"><i class="bi bi-alarm-fill"></i></span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

@endsection

<script>
document.querySelectorAll('.task-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        if (this.checked) {
            const taskId = this.dataset.taskId;
            fetch(`/api/tasks/${taskId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    this.closest('.task-item').style.opacity = '0.6';
                    this.closest('.task-item').style.textDecoration = 'line-through';
                }
            });
        }
    });
});
</script>
