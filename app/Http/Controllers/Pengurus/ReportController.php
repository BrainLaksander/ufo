<?php

namespace App\Http\Controllers\Pengurus;

use App\Http\Controllers\Controller;
use App\Models\Workflow\Report as WorkflowReport;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ReportController extends Controller
{
    use PengurusControllerTrait;

    public function store(Request $request): RedirectResponse
    {
        $context = $this->resolvePengurusContext($request);

        if (!$context['organization_id'] || !$context['member_id']) {
            return back()->with('error', $this->refLabel('flash_message', 'pengurus_data_incomplete'));
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'content' => 'required|string|max:5000',
            'report_type' => 'nullable|string|max:80',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        return back()->with('success', 'Laporan berhasil disimpan.');
    }

    public function submit(Request $request, int $id): RedirectResponse
    {
        return back()->with('success', 'Laporan berhasil disubmit.');
    }

    public function review(Request $request, int $id): RedirectResponse
    {
        return back()->with('success', 'Laporan berhasil direview.');
    }
}
