<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use App\Services\ReferenceValueService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use KemahasiswaanControllerTrait;

    public function __construct(ReferenceValueService $referenceService)
    {
        $this->referenceService = $referenceService;
    }

    public function dashboard(): View
    {
        $organizationActiveStatus = $this->organizationActiveStatus();
        $ongoingEventStatuses = $this->ongoingEventStatuses();
        $pendingSubmissionStatuses = $this->pendingSubmissionStatuses();
        $pendingReportStatuses = $this->pendingReportStatuses();

        $totalOrganizations = Schema::hasTable('organizations')
            ? (int) DB::table('organizations')->where('status', $organizationActiveStatus)->count()
            : 0;

        $totalOngoingEvents = Schema::hasTable('events')
            ? (int) DB::table('events')->whereIn('status', $ongoingEventStatuses)->count()
            : 0;

        $pendingSubmissions = Schema::hasTable('submissions')
            ? (int) DB::table('submissions')->whereIn('status', $pendingSubmissionStatuses)->count()
            : 0;

        $pendingReports = Schema::hasTable('reports')
            ? (int) DB::table('reports')->whereIn('status', $pendingReportStatuses)->count()
            : 0;

        $monthlyActivity = $this->buildMonthlyActivity();
        $chartMax = max(1, (int) collect($monthlyActivity)->max('value'));

        $stats = [
            ['label' => 'Total Organisasi Aktif', 'value' => (string) $totalOrganizations, 'icon' => 'bi-buildings', 'tone' => 'primary'],
            ['label' => 'Total Kegiatan Berjalan', 'value' => (string) $totalOngoingEvents, 'icon' => 'bi-card-list', 'tone' => 'primary'],
            ['label' => 'Total Kegiatan Menunggu Persetujuan', 'value' => (string) $pendingSubmissions, 'icon' => 'bi-clock-history', 'tone' => 'warning'],
            ['label' => 'Total Laporan Belum Direview', 'value' => (string) $pendingReports, 'icon' => 'bi-clipboard-check', 'tone' => 'primary'],
        ];

        $recentAnnouncements = collect($this->getPengumuman())
            ->take(6)
            ->map(function (array $item) {
                $dateSource = $item['publish_at'] ?? $item['created_at'] ?? null;
                return [
                    'judul' => (string) ($item['judul'] ?? '-'),
                    'tanggal' => $dateSource ? Carbon::parse((string) $dateSource)->translatedFormat('d M Y') : '-',
                    'status' => (string) ($item['status'] ?? '-'),
                ];
            })
            ->values()
            ->all();

        return view('pages.kemahasiswaan.dashboard', [
            'stats' => $stats,
            'monthlyActivity' => $monthlyActivity,
            'upcomingEvents' => $this->getUpcomingEvents(),
            'chartMax' => $chartMax,
            'recentAnnouncements' => $recentAnnouncements,
            'ui' => $this->buildDashboardUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function notifikasiIndex(Request $request): View
    {
        $selectedFilter = Str::lower((string) $request->query('jenis', 'semua'));

        $allNotifications = $this->getSystemNotifications();
        $types = $this->notificationTypeOptions();
        $allowedFilters = collect($types)->pluck('value')->filter(fn ($v) => trim((string) $v) !== '')->values()->all();

        if (!in_array($selectedFilter, $allowedFilters, true)) {
            $selectedFilter = 'semua';
        }

        $filteredNotifications = $selectedFilter === 'semua'
            ? $allNotifications
            : array_values(array_filter($allNotifications, fn (array $item) => ($item['jenis'] ?? '') === $selectedFilter));

        $counts = collect($allNotifications)->countBy('jenis')->all();

        $types = collect($types)
            ->map(function (array $type) use ($counts, $allNotifications) {
                return [
                    'value' => $type['value'],
                    'label' => $type['label'],
                    'count' => $type['value'] === 'semua' ? count($allNotifications) : (int) ($counts[$type['value']] ?? 0),
                ];
            })
            ->values()
            ->all();

        return view('pages.kemahasiswaan.notifikasi', [
            'notifikasiItems' => $filteredNotifications,
            'notifikasiFilter' => $selectedFilter,
            'notifikasiTypes' => $types,
            'notifikasiSummary' => [
                'total' => count($allNotifications),
                'belum_dibaca' => collect($allNotifications)->where('status', 'belum_dibaca')->count(),
            ],
            'ui' => $this->buildNotifikasiUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function kontakPengurusIndex(): View
    {
        $kontakPengurus = $this->getKontakPengurusUkm();

        return view('pages.kemahasiswaan.kontak', [
            'kontakPengurus' => $kontakPengurus,
            'contactSummary' => [
                'total_kontak' => count($kontakPengurus),
                'dengan_email' => collect($kontakPengurus)->filter(fn (array $item) => trim((string) ($item['email'] ?? '')) !== '')->count(),
                'dengan_kontak' => collect($kontakPengurus)->filter(fn (array $item) => trim((string) ($item['kontak'] ?? '')) !== '')->count(),
                'total_organisasi' => collect($kontakPengurus)->pluck('organisasi')->filter(fn ($n) => trim((string) $n) !== '')->unique()->count(),
            ],
            'ui' => $this->buildKontakUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    public function kalenderKegiatanIndex(): View
    {
        $kalenderKegiatan = $this->getKalenderKegiatanKampus();
        $now = now();

        return view('pages.kemahasiswaan.kalender', [
            'kalenderKegiatan' => $kalenderKegiatan,
            'organizations' => $this->getOrganizations(),
            'kalenderSummary' => [
                'total' => count($kalenderKegiatan),
                'bulan_ini' => collect($kalenderKegiatan)->filter(function (array $item) use ($now) {
                    if (empty($item['tanggal_raw'])) return false;
                    $start = Carbon::parse((string) $item['tanggal_raw'])->startOfDay();
                    $end = Carbon::parse((string) ($item['tanggal_selesai_raw'] ?? $item['tanggal_raw']))->endOfDay();
                    $monthStart = $now->copy()->startOfMonth()->startOfDay();
                    $monthEnd = $now->copy()->endOfMonth()->endOfDay();
                    return $start->lte($monthEnd) && $end->gte($monthStart);
                })->count(),
                '7_hari' => collect($kalenderKegiatan)->filter(function (array $item) use ($now) {
                    if (empty($item['tanggal_raw'])) return false;
                    $start = Carbon::parse((string) $item['tanggal_raw'])->startOfDay();
                    $end = Carbon::parse((string) ($item['tanggal_selesai_raw'] ?? $item['tanggal_raw']))->endOfDay();
                    $rangeStart = $now->copy()->startOfDay();
                    $rangeEnd = $now->copy()->addDays(7)->endOfDay();
                    return $start->lte($rangeEnd) && $end->gte($rangeStart);
                })->count(),
            ],
            'ui' => $this->buildKalenderUiText(),
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    // ============ Private Helpers ============

    private function buildMonthlyActivity(): array
    {
        $months = collect(range(0, 11))->map(fn ($i) => now()->subMonths($i)->startOfMonth())->reverse()->values();

        $counts = collect();
        if (Schema::hasTable('events')) {
            $events = DB::table('events')->select(DB::raw('MONTH(start_date) as month, COUNT(*) as count'))
                ->whereYear('start_date', now()->year)->groupBy(DB::raw('MONTH(start_date)'))->get();
            foreach ($events as $event) {
                $counts[(int) $event->month] = (int) $event->count;
            }
        }

        return $months->map(function (Carbon $month) use ($counts) {
            return [
                'bulan' => $month->translatedFormat('M'),
                'value' => $counts[$month->month] ?? 0,
            ];
        })->all();
    }

    private function getUpcomingEvents(): array
    {
        if (!Schema::hasTable('events')) {
            return [];
        }

        $rows = DB::table('events as evt')
            ->leftJoin('organizations as org', 'org.id', '=', 'evt.organization_id')
            ->select(['evt.name', 'evt.start_date', 'evt.status', 'org.shortname', 'org.name as organization_name'])
            ->whereDate('evt.start_date', '>=', now()->toDateString())
            ->orderBy('evt.start_date')
            ->limit(6)
            ->get();

        return $rows->map(function ($row) {
            $title = (string) $row->name;
            if (!empty($row->shortname)) {
                $title .= ' - ' . (string) $row->shortname;
            } elseif (!empty($row->organization_name)) {
                $title .= ' - ' . (string) $row->organization_name;
            }

            $statusCode = Str::lower((string) $row->status);
            [$statusLabel, $tone] = $this->eventDashboardStatus($statusCode);

            return [
                'title' => $title,
                'date' => Carbon::parse($row->start_date)->translatedFormat('d F Y'),
                'status' => $statusLabel,
                'tone' => $tone,
            ];
        })->all();
    }

    private function getPengumuman(): array
    {
        $rows = DB::table('kemahasiswaan_announcements as ann')
            ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
            ->select(['ann.id', 'ann.title', 'ann.publish_status', 'ann.publish_at', 'ann.created_at'])
            ->orderByDesc('ann.id')
            ->get();

        return $rows->map(fn ($row) => [
            'id' => (int) $row->id,
            'judul' => $row->title,
            'publish_at' => $row->publish_at,
            'created_at' => $row->created_at,
            'status' => Str::title((string) $row->publish_status),
        ])->all();
    }

    private function getKontakPengurusUkm(): array
    {
        $rows = DB::table('kemahasiswaan_ukm_accounts as akun')
            ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
            ->select(['akun.name', 'akun.email', 'akun.status', 'org.name as organisasi'])
            ->get();

        return $rows->map(fn ($row) => [
            'nama' => (string) $row->name,
            'organisasi' => (string) ($row->organisasi ?? '-'),
            'kontak' => '-',
            'email' => (string) $row->email,
            'status_code' => (string) $row->status,
            'status_label' => Str::lower((string) $row->status) === 'active' ? 'Aktif' : 'Nonaktif',
        ])->all();
    }

    private function getKalenderKegiatanKampus(): array
    {
        if (Schema::hasTable('kemahasiswaan_schedules')) {
            $columns = Schema::getColumnListing('kemahasiswaan_schedules');
            $columnExists = static fn (string $column): bool => in_array($column, $columns, true);

            $select = ['sch.id', 'sch.title', 'sch.start_at', 'sch.end_at', 'sch.location', 'sch.status', 'org.name as organization_name'];

            if ($columnExists('category')) {
                $select[] = 'sch.category';
            }

            if ($columnExists('description')) {
                $select[] = 'sch.description';
            }

            $rows = DB::table('kemahasiswaan_schedules as sch')
                ->leftJoin('organizations as org', 'org.id', '=', 'sch.organization_id')
                ->select($select)
                ->orderBy('sch.start_at')
                ->limit(200)
                ->get();

            return $rows->map(function ($row) use ($columnExists) {
                $startDate = $row->start_at ? Carbon::parse((string) $row->start_at) : null;
                $endDate = $row->end_at ? Carbon::parse((string) $row->end_at) : $startDate;

                return [
                    'id' => (int) $row->id,
                    'judul' => (string) $row->title,
                    'organisasi' => (string) ($row->organization_name ?? '-'),
                    'tanggal' => $startDate ? $startDate->translatedFormat('d F Y') : '-',
                    'tanggal_raw' => $startDate?->toDateString(),
                    'tanggal_selesai_raw' => $endDate?->toDateString(),
                    'lokasi' => (string) ($row->location ?? '-'),
                    'kategori' => $columnExists('category') ? (string) ($row->category ?? '') : '',
                    'deskripsi' => $columnExists('description') ? (string) ($row->description ?? '') : '',
                    'can_delete' => true,
                ];
            })->all();
        }

        if (!Schema::hasTable('events')) {
            return [];
        }

        $rows = DB::table('events as evt')
            ->leftJoin('organizations as org', 'org.id', '=', 'evt.organization_id')
            ->select(['evt.id', 'evt.name', 'evt.start_date', 'evt.end_date', 'evt.location', 'org.name as organization_name'])
            ->orderBy('evt.start_date')
            ->limit(200)
            ->get();

        return $rows->map(function ($row) {
            $startDate = $row->start_date ? Carbon::parse((string) $row->start_date) : null;
            $endDate = $row->end_date ? Carbon::parse((string) $row->end_date) : $startDate;
            return [
                'id' => (int) $row->id,
                'judul' => (string) $row->name,
                'organisasi' => (string) ($row->organization_name ?? '-'),
                'tanggal' => $startDate ? $startDate->translatedFormat('d F Y') : '-',
                'tanggal_raw' => $startDate?->toDateString(),
                'tanggal_selesai_raw' => $endDate?->toDateString(),
                'lokasi' => (string) ($row->location ?? '-'),
                'kategori' => '',
                'deskripsi' => '',
                'can_delete' => false,
            ];
        })->all();
    }

    private function getOrganizations(): array
    {
        return DB::table('organizations')
            ->select(['id', 'name', 'shortname'])
            ->where('status', $this->organizationActiveStatus())
            ->orderBy('name')
            ->get()
            ->map(fn ($row) => ['id' => (int) $row->id, 'name' => $row->name, 'shortname' => $row->shortname])
            ->all();
    }

    private function getSystemNotifications(): array
    {
        $items = [];

        if (Schema::hasTable('submissions')) {
            $rows = DB::table('submissions as sub')
                ->leftJoin('organizations as org', 'org.id', '=', 'sub.organization_id')
                ->select(['sub.id', 'sub.title', 'sub.status', 'sub.updated_at', 'org.name as organization_name'])
                ->whereNotIn('sub.status', ['draft'])
                ->orderByDesc('sub.updated_at')
                ->limit(30)
                ->get();

            foreach ($rows as $row) {
                $timestamp = Carbon::parse($row->updated_at)->timestamp;
                $items[] = [
                    'id' => 'submission-' . (int) $row->id,
                    'jenis' => 'pengajuan',
                    'judul' => 'Update Pengajuan Kegiatan',
                    'pesan' => ($row->organization_name ?: 'Organisasi') . ' mengajukan "' . Str::limit((string) $row->title, 80) . '".',
                    'status' => 'belum_dibaca',
                    'waktu' => Carbon::createFromTimestamp($timestamp)->diffForHumans(),
                    'sort_time' => $timestamp,
                ];
            }
        }

        if (Schema::hasTable('kemahasiswaan_announcements')) {
            $rows = DB::table('kemahasiswaan_announcements as ann')
                ->leftJoin('kemahasiswaan_ukm_accounts as akun', 'akun.id', '=', 'ann.ukm_account_id')
                ->leftJoin('organizations as org', 'org.id', '=', 'akun.organization_id')
                ->select(['ann.id', 'ann.title', 'ann.updated_at', 'org.name as organization_name'])
                ->orderByDesc('ann.updated_at')
                ->limit(30)
                ->get();

            foreach ($rows as $row) {
                $timestamp = Carbon::parse($row->updated_at)->timestamp;
                $items[] = [
                    'id' => 'announcement-' . (int) $row->id,
                    'jenis' => 'pengumuman',
                    'judul' => 'Review Distribusi Pengumuman',
                    'pesan' => 'Pengumuman "' . Str::limit((string) $row->title, 80) . '" dari ' . ($row->organization_name ?: 'Organisasi') . ' diperbarui.',
                    'status' => 'sudah_dibaca',
                    'waktu' => Carbon::createFromTimestamp($timestamp)->diffForHumans(),
                    'sort_time' => $timestamp,
                ];
            }
        }

        usort($items, fn (array $a, array $b) => ($b['sort_time'] ?? 0) <=> ($a['sort_time'] ?? 0));

        return collect($items)
            ->take(80)
            ->map(function (array $item) {
                unset($item['sort_time']);
                return $item;
            })
            ->values()
            ->all();
    }

    private function notificationTypeOptions(): array
    {
        return [
            ['value' => 'semua', 'label' => 'Semua Notifikasi'],
            ['value' => 'pengajuan', 'label' => 'Pengajuan Kegiatan'],
            ['value' => 'pengumuman', 'label' => 'Review Pengumuman'],
        ];
    }

    private function uiText(string $code): string
    {
        return $this->referenceService->getLabel('ui_text', $code);
    }

    private function uiTextMap(array $keyCodeMap): array
    {
        $labels = [];

        foreach ($keyCodeMap as $key => $code) {
            $labels[$key] = $this->uiText((string) $code);
        }

        return $labels;
    }

    private function buildDashboardUiText(): array
    {
        return $this->uiTextMap([
            'chart_title' => 'kmh_dashboard_chart_title',
            'quick_actions_title' => 'kmh_dashboard_quick_actions_title',
            'quick_action_review' => 'kmh_dashboard_quick_action_review',
            'quick_action_announcement' => 'kmh_dashboard_quick_action_announcement',
            'upcoming_events_title' => 'kmh_dashboard_upcoming_events_title',
            'upcoming_empty_title' => 'kmh_dashboard_upcoming_empty_title',
            'upcoming_empty_message' => 'kmh_dashboard_upcoming_empty_message',
            'recent_announcements_title' => 'kmh_dashboard_recent_announcements_title',
            'view_all_label' => 'kmh_common_view_all',
            'table_col_title' => 'kmh_dashboard_table_col_title',
            'table_col_date' => 'kmh_dashboard_table_col_date',
            'table_col_status' => 'kmh_dashboard_table_col_status',
            'recent_empty' => 'kmh_dashboard_recent_empty',
        ]);
    }

    private function buildNotifikasiUiText(): array
    {
        return $this->uiTextMap([
            'total_notifications' => 'kmh_notification_total',
            'unread_notifications' => 'kmh_notification_unread',
            'filter_title' => 'kmh_notification_filter_title',
            'filter_showing_items' => 'kmh_notification_filter_showing_items',
            'summary_prefix' => 'kmh_notification_summary_prefix',
            'summary_suffix' => 'kmh_notification_summary_suffix',
            'reset_filter' => 'kmh_notification_reset_filter',
            'open_submission_review' => 'kmh_notification_open_submission_review',
            'detail_button' => 'kmh_notification_detail_button',
            'empty_state' => 'kmh_notification_empty_state',
        ]);
    }

    private function buildKontakUiText(): array
    {
        return $this->uiTextMap([
            'hero_title' => 'kmh_contact_hero_title',
            'hero_subtitle' => 'kmh_contact_hero_subtitle',
            'search_placeholder' => 'kmh_contact_search_placeholder',
            'search_aria' => 'kmh_contact_search_aria',
            'total_org_label' => 'kmh_contact_total_org_label',
            'bem_label' => 'kmh_contact_bem_label',
            'ukm_label' => 'kmh_contact_ukm_label',
            'contact_unavailable' => 'kmh_contact_unavailable',
            'email_unavailable' => 'kmh_email_unavailable',
            'empty_state' => 'kmh_contact_empty_state',
            'search_empty_state' => 'kmh_contact_search_empty_state',
        ]);
    }

    private function buildKalenderUiText(): array
    {
        return $this->uiTextMap([
            'calendar_title' => 'kmh_calendar_title',
            'calendar_subtitle' => 'kmh_calendar_subtitle',
            'add_activity' => 'kmh_calendar_add_activity',
            'modal_title' => 'kmh_calendar_modal_title',
            'modal_subtitle' => 'kmh_calendar_modal_subtitle',
            'field_title' => 'kmh_calendar_field_title',
            'field_start_date' => 'kmh_calendar_field_start_date',
            'field_end_date' => 'kmh_calendar_field_end_date',
            'field_category' => 'kmh_calendar_field_category',
            'field_organization' => 'kmh_calendar_field_organization',
            'field_location' => 'kmh_calendar_field_location',
            'field_description' => 'kmh_calendar_field_description',
            'field_title_placeholder' => 'kmh_calendar_field_title_placeholder',
            'field_location_placeholder' => 'kmh_calendar_field_location_placeholder',
            'field_description_placeholder' => 'kmh_calendar_field_description_placeholder',
            'schedule_form_warning' => 'kmh_schedule_form_warning',
            'schedule_org_placeholder' => 'kmh_schedule_org_placeholder',
            'save_button' => 'kmh_calendar_save_button',
            'cancel_button' => 'kmh_calendar_cancel_button',
            'filter_category' => 'kmh_calendar_filter_category',
            'search_label' => 'kmh_calendar_search_label',
            'search_placeholder' => 'kmh_calendar_search_placeholder',
            'legend_label' => 'kmh_calendar_legend_label',
            'all_activities' => 'kmh_calendar_all_activities',
            'month_view' => 'kmh_calendar_month_view',
            'list_view' => 'kmh_calendar_list_view',
            'more_suffix' => 'kmh_calendar_more_suffix',
            'table_col_title' => 'kmh_calendar_table_col_title',
            'table_col_org' => 'kmh_calendar_table_col_org',
            'table_col_date' => 'kmh_calendar_table_col_date',
            'table_col_location' => 'kmh_calendar_table_col_location',
            'table_col_category' => 'kmh_calendar_table_col_category',
            'empty_state' => 'kmh_calendar_empty_state',
            'category_akademik' => 'kmh_calendar_category_akademik',
            'category_organisasi' => 'kmh_calendar_category_organisasi',
            'category_masa_tenang' => 'kmh_calendar_category_masa_tenang',
            'category_libur' => 'kmh_calendar_category_libur',
            'category_event_besar' => 'kmh_calendar_category_event_besar',
        ]);
    }

    private function eventDashboardStatus(string $status): array
    {
        return match (Str::lower($status)) {
            'approved', 'ongoing' => ['Berjalan', 'primary'],
            'completed' => ['Selesai', 'success'],
            'cancelled' => ['Batal', 'danger'],
            'pending', 'draft' => ['Menunggu', 'warning'],
            default => [Str::title(str_replace('_', ' ', $status)), 'secondary'],
        };
    }

    private function ongoingEventStatuses(): array
    {
        return ['approved', 'ongoing'];
    }

    private function pendingSubmissionStatuses(): array
    {
        return ['submitted', 'reviewing', 'revised'];
    }

    private function pendingReportStatuses(): array
    {
        return ['submitted', 'reviewing', 'revision_needed'];
    }
}
