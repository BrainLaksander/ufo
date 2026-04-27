<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Submission as WorkflowSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubmissionController extends Controller
{
    use PengurusControllerTrait;

    public function index(Request $request): View
    {
        $context = $this->resolvePengurusContext($request);
        $organizationId = (int) ($context['organization_id'] ?? 0);

        $workflowPengajuan = $this->loadSubmissionCards($organizationId);
        $workflowLaporan = $this->loadReportCards($organizationId);
        $jadwalKegiatan = $this->loadEventScheduleCards($organizationId);
        $kontakPengurus = $this->loadContactCards($organizationId);
        $eventOptions = $this->loadEventOptions($organizationId);

        return view('pages.pengurus.proposals', [
            'workflowPengajuan' => $workflowPengajuan,
            'workflowLaporan' => $workflowLaporan,
            'jadwalKegiatan' => $jadwalKegiatan,
            'kontakPengurus' => $kontakPengurus,
            'eventOptions' => $eventOptions,
            'hasPengurusContext' => $organizationId > 0,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id'] || !$context['member_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'pengurus_data_incomplete'));
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'description' => 'required|string|max:3000',
            'type' => 'required|in:proposal,budget,activity_plan',
            'proposal_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        return back()->with('success', $this->refLabel('flash_message', 'proposal_created'));
    }

    public function submit(Request $request, int $id): RedirectResponse
    {
        return back()->with('success', 'Pengajuan berhasil disubmit.');
    }

    public function review(Request $request, int $id): RedirectResponse
    {
        return back()->with('success', 'Pengajuan berhasil direview.');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadSubmissionCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('submissions')) {
            return [];
        }

        $organizationName = (string) (DB::table('organizations')->where('id', $organizationId)->value('name') ?? 'Organisasi');

        return DB::table('submissions')
            ->where('organization_id', $organizationId)
            ->orderByDesc('submitted_date')
            ->orderByDesc('created_at')
            ->get(['id', 'title', 'description', 'type', 'status', 'feedback', 'submitted_date', 'file_path'])
            ->map(function ($row) use ($organizationName) {
                $submittedAt = !empty($row->submitted_date)
                    ? Carbon::parse((string) $row->submitted_date)
                    : Carbon::parse((string) ($row->created_at ?? now()));

                return [
                    'id' => (int) $row->id,
                    'judul' => (string) $row->title,
                    'tipe' => Str::title(str_replace('_', ' ', (string) ($row->type ?? 'proposal'))),
                    'organisasi' => $organizationName,
                    'tanggal_kegiatan' => $submittedAt->toDateString(),
                    'deskripsi' => (string) ($row->description ?? ''),
                    'file_path' => $row->file_path ?? '',
                    'status' => $this->submissionStatusLabel((string) ($row->status ?? 'draft')),
                    'catatan_departemen' => (string) ($row->feedback ?? ''),
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadReportCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('reports')) {
            return [];
        }

        $organizationName = (string) (DB::table('organizations')->where('id', $organizationId)->value('name') ?? 'Organisasi');

        return DB::table('reports')
            ->where('organization_id', $organizationId)
            ->orderByDesc('submitted_date')
            ->orderByDesc('created_at')
            ->get(['id', 'title', 'content', 'report_type', 'status', 'reviewer_notes', 'submitted_date', 'attachment'])
            ->map(function ($row) use ($organizationName) {
                $submittedAt = !empty($row->submitted_date)
                    ? Carbon::parse((string) $row->submitted_date)
                    : Carbon::parse((string) ($row->created_at ?? now()));

                return [
                    'id' => (int) $row->id,
                    'judul' => (string) $row->title,
                    'tipe' => Str::title(str_replace('_', ' ', (string) ($row->report_type ?? 'activity'))),
                    'organisasi' => $organizationName,
                    'tanggal_laporan' => $submittedAt->toDateString(),
                    'konten' => (string) ($row->content ?? ''),
                    'attachment' => $row->attachment ?? '',
                    'status' => $this->submissionStatusLabel((string) ($row->status ?? 'draft')),
                    'catatan_departemen' => (string) ($row->reviewer_notes ?? ''),
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadEventScheduleCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('events')) {
            return [];
        }

        $organizationName = (string) (DB::table('organizations')->where('id', $organizationId)->value('name') ?? 'Organisasi');

        return DB::table('events')
            ->where('organization_id', $organizationId)
            ->orderBy('start_date')
            ->limit(12)
            ->get(['id', 'name', 'start_date', 'location', 'status'])
            ->map(function ($row) use ($organizationName) {
                return [
                    'id' => (int) $row->id,
                    'judul' => (string) $row->name,
                    'organisasi' => $organizationName,
                    'tanggal' => Carbon::parse((string) $row->start_date)->toDateString(),
                    'lokasi' => (string) ($row->location ?? '-'),
                    'status' => Str::lower((string) ($row->status ?? 'draft')),
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadContactCards(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('members')) {
            return [];
        }

        $organizationName = (string) (DB::table('organizations')->where('id', $organizationId)->value('name') ?? 'Organisasi');

        return DB::table('members')
            ->where('organization_id', $organizationId)
            ->where('status', 'aktif')
            ->orderByRaw("CASE position WHEN 'ketua' THEN 0 WHEN 'sekretaris' THEN 1 WHEN 'bendahara' THEN 2 ELSE 3 END")
            ->orderBy('name')
            ->limit(12)
            ->get(['id', 'name', 'position', 'phone', 'email'])
            ->map(function ($row) use ($organizationName) {
                $contactNumber = (string) ($row->phone ?? '-');

                return [
                    'id' => (int) $row->id,
                    'nama' => (string) $row->name,
                    'jabatan' => Str::title((string) ($row->position ?? 'staff')),
                    'organisasi' => $organizationName,
                    'kontak' => $contactNumber,
                    'whatsapp' => $contactNumber,
                    'email' => (string) ($row->email ?? '-'),
                    'status' => 'active',
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function loadEventOptions(int $organizationId): array
    {
        if ($organizationId <= 0 || !Schema::hasTable('events')) {
            return [];
        }

        return DB::table('events')
            ->where('organization_id', $organizationId)
            ->orderByDesc('start_date')
            ->limit(20)
            ->get(['id', 'name'])
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => (string) $row->name,
            ])
            ->all();
    }

    private function submissionStatusLabel(string $status): string
    {
        return match (Str::lower($status)) {
            'draft' => 'Draft',
            'submitted' => 'Diajukan',
            'reviewing' => 'Sedang Direview',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'revised' => 'Revisi',
            'revision_needed' => 'Perlu Revisi',
            default => Str::title(str_replace('_', ' ', $status)),
        };
    }
}
