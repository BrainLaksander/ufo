<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KemahasiswaanController extends Controller
{
    use PengurusControllerTrait;

    public function index(): View
    {
        $sessionUser = request()->session()->get('user');
        $organizationId = is_array($sessionUser)
            ? (int) ($sessionUser['organization_id'] ?? 0)
            : 0;

        return view('portal.kemahasiswaan.pengajuan', [
            'workflowPengajuan' => $this->loadKemahasiswaanSubmissions($organizationId),
            'workflowLaporan' => $this->loadKemahasiswaanReports($organizationId),
            'jadwalKegiatan' => $this->loadKemahasiswaanSchedules($organizationId),
            'organizations' => $this->loadKemahasiswaanOrganizations(),
            'ui' => [],
            'headerNotificationCount' => $this->getNotificationCounter(),
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadKemahasiswaanSubmissions(int $organizationId): array
    {
        if (!Schema::hasTable('submissions')) {
            return [];
        }

        $query = DB::table('submissions as sub')
            ->leftJoin('organizations as org', 'org.id', '=', 'sub.organization_id')
            ->select(['sub.id', 'sub.title', 'sub.description', 'sub.type', 'sub.status', 'sub.feedback', 'sub.submitted_date', 'sub.file_path', 'org.name as organization_name'])
            ->orderByDesc('sub.submitted_date')
            ->orderByDesc('sub.created_at');

        if ($organizationId > 0) {
            $query->where('sub.organization_id', $organizationId);
        }

        return $query->get()->map(function ($row) {
            $submittedAt = !empty($row->submitted_date)
                ? Carbon::parse((string) $row->submitted_date)
                : Carbon::parse((string) ($row->created_at ?? now()));

            return [
                'id' => (int) $row->id,
                'judul' => (string) $row->title,
                'tipe' => Str::title(str_replace('_', ' ', (string) ($row->type ?? 'proposal'))),
                'organisasi' => (string) ($row->organization_name ?? 'Organisasi'),
                'tanggal_kegiatan' => $submittedAt->toDateString(),
                'deskripsi' => (string) ($row->description ?? ''),
                'file_path' => $row->file_path ?? '',
                'status' => $this->loadStatusLabel((string) ($row->status ?? 'draft')),
                'catatan_departemen' => (string) ($row->feedback ?? ''),
            ];
        })->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadKemahasiswaanReports(int $organizationId): array
    {
        if (!Schema::hasTable('reports')) {
            return [];
        }

        $query = DB::table('reports as rpt')
            ->leftJoin('organizations as org', 'org.id', '=', 'rpt.organization_id')
            ->select(['rpt.id', 'rpt.title', 'rpt.content', 'rpt.report_type', 'rpt.status', 'rpt.reviewer_notes', 'rpt.submitted_date', 'rpt.attachment', 'org.name as organization_name'])
            ->orderByDesc('rpt.submitted_date')
            ->orderByDesc('rpt.created_at');

        if ($organizationId > 0) {
            $query->where('rpt.organization_id', $organizationId);
        }

        return $query->get()->map(function ($row) {
            $submittedAt = !empty($row->submitted_date)
                ? Carbon::parse((string) $row->submitted_date)
                : Carbon::parse((string) ($row->created_at ?? now()));

            return [
                'id' => (int) $row->id,
                'judul' => (string) $row->title,
                'tipe' => Str::title(str_replace('_', ' ', (string) ($row->report_type ?? 'activity'))),
                'organisasi' => (string) ($row->organization_name ?? 'Organisasi'),
                'tanggal_laporan' => $submittedAt->toDateString(),
                'konten' => (string) ($row->content ?? ''),
                'attachment' => $row->attachment ?? '',
                'status' => $this->loadStatusLabel((string) ($row->status ?? 'draft')),
                'catatan_departemen' => (string) ($row->reviewer_notes ?? ''),
            ];
        })->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadKemahasiswaanSchedules(int $organizationId): array
    {
        if (!Schema::hasTable('events')) {
            return [];
        }

        $query = DB::table('events as event')
            ->leftJoin('organizations as org', 'org.id', '=', 'event.organization_id')
            ->select(['event.id', 'event.name', 'event.start_date', 'event.location', 'org.name as organization_name'])
            ->orderBy('event.start_date')
            ->limit(20);

        if ($organizationId > 0) {
            $query->where('event.organization_id', $organizationId);
        }

        return $query->get()->map(function ($row) {
            return [
                'id' => (int) $row->id,
                'judul' => (string) $row->name,
                'organisasi' => (string) ($row->organization_name ?? 'Organisasi'),
                'tanggal' => Carbon::parse((string) $row->start_date)->toDateString(),
                'lokasi' => (string) ($row->location ?? '-'),
            ];
        })->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadKemahasiswaanOrganizations(): array
    {
        if (!Schema::hasTable('organizations')) {
            return [];
        }

        return DB::table('organizations')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'shortname'])
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => (string) $row->name,
                'shortname' => (string) ($row->shortname ?? ''),
            ])
            ->all();
    }

    private function loadStatusLabel(string $status): string
    {
        return match (Str::lower($status)) {
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revised' => 'Revisi',
            default => Str::title(str_replace('_', ' ', $status)),
        };
    }
}
