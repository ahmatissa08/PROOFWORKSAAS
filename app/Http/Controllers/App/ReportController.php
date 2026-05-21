<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Mail\ReportSharedMail;
use App\Models\Project;
use App\Models\Report;
use App\Models\ReportEntry;
use App\Services\AiSummaryService;
use App\Services\ReportGeneratorService;
use App\Services\ReportPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ReportController extends Controller
{
    public function __construct(
        protected ReportGeneratorService $generator,
        protected AiSummaryService $ai,
        protected ReportPdfService $pdf,
    ) {}

    public function index()
    {
        $reports = Auth::user()->reports()
            ->with(['project', 'client'])
            ->orderByDesc('period_end')
            ->paginate(20);

        return view('app.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);
        $report->load(['project.integrations', 'client', 'entries']);

        return view('app.reports.show', compact('report'));
    }

    // Generate a new report for a project.
    public function generate(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
        ]);

        $report = $this->generator->generate(
            $project,
            $request->date('period_start')->startOfDay(),
            $request->date('period_end')->endOfDay(),
        );

        // Generate AI summary.
        try {
            $summary = $this->ai->summarize($report);
            $report->update(['ai_summary' => $summary]);
        } catch (Throwable $e) {
            \Log::warning('AI summary failed: '.$e->getMessage());
        }

        return redirect()->route('reports.show', $report)
            ->with('success', 'Report generated successfully!');
    }

    // Edit report (add manual entries, edit summary).
    public function edit(Report $report)
    {
        $this->authorize('update', $report);
        $report->load(['project.integrations', 'client', 'entries']);

        return view('app.reports.edit', compact('report'));
    }

    /**
     * Download report as signed PDF
     */
    public function downloadPdf(Report $report)
    {
        $this->authorize('view', $report);

        $path = $this->pdf->generate($report);

        return response()->download($path,
            'proofwork-report-'.$report->id.'.pdf',
            ['Content-Type' => 'application/pdf']
        )->deleteFileAfterSend();
    }

    public function update(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'ai_summary' => ['nullable', 'string', 'max:2000'],
            'status' => ['in:draft,ready,sent'],
        ]);

        $report->update($validated);

        return redirect()->route('reports.show', $report)->with('success', 'Report updated.');
    }

    // Add manual entry.
    public function addEntry(Request $request, Report $report)
    {
        $this->authorize('update', $report);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:500'],
            'source' => ['required', 'in:github,linear,notion,google_calendar,manual'],
            'type' => ['required', 'string', 'max:50'],
            'source_url' => ['nullable', 'url'],
            'occurred_at' => ['nullable', 'date'],
        ]);

        $report->entries()->create($validated);

        return back()->with('success', 'Entry added.');
    }

    public function deleteEntry(Report $report, ReportEntry $entry)
    {
        $this->authorize('update', $report);
        abort_unless($entry->report_id === $report->id, 404);
        $entry->delete();

        return back()->with('success', 'Entry removed.');
    }

    // Send report to client.
    public function send(Report $report)
    {
        $this->authorize('update', $report);

        if (! $report->client || ! $report->client->email) {
            return back()->withErrors(['send' => 'No client email found. Add a client email first.']);
        }

        try {
            Mail::to($report->client->email)
                ->send(new ReportSharedMail($report));
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'send' => 'The report email could not be sent. Check the mail configuration and try again.',
            ]);
        }

        $report->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', "Report sent to {$report->client->email}!");
    }

    // Public report (client view, no auth).
    public function publicView(string $token)
    {
        $report = Report::where('share_token', $token)
            ->where('share_enabled', true)
            ->firstOrFail();

        $report->increment('view_count');
        $report->load(['project', 'client', 'entries']);

        return view('app.reports.public', compact('report'));
    }

    public function destroy(Report $report)
    {
        $this->authorize('delete', $report);
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Report deleted.');
    }
}
