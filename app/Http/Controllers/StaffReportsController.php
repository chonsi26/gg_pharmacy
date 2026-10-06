<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Staff-side Reports page.
 *
 * Mirrors the reports screen + endpoints of AdminController (reports,
 * storeReport, updateReport, destroyReport) so staff and admin always work
 * on the same `reports` table. Only the guard (auth:staff), route names
 * (staff.reports.*) and the view (staffs.reports) differ.
 */
class StaffReportsController extends Controller
{
    // ── Page ─────────────────────────────────────────────────────────────────

    public function index(): View
    {
        $logo2    = Setting::get('logo2');
        $siteName = Setting::get('site_name', 'Pharmacy');

        $reports = Report::orderByDesc('report_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Report $report) => $this->formatReport($report))
            ->values();

        return view('staffs.reports', compact('logo2', 'siteName', 'reports'));
    }

    // ── Create ───────────────────────────────────────────────────────────────

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validatedReportData($request);

        $report = Report::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Report \"{$report->report_title}\" added successfully.",
                'report'  => $this->formatReport($report),
            ], 201);
        }

        return redirect()->route('staff.reports')
            ->with('status', "Report \"{$report->report_title}\" added successfully.");
    }

    // ── Update ───────────────────────────────────────────────────────────────

    public function update(Request $request, Report $report): JsonResponse|RedirectResponse
    {
        $data = $this->validatedReportData($request);

        $report->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Report \"{$report->report_title}\" updated successfully.",
                'report'  => $this->formatReport($report),
            ]);
        }

        return redirect()->route('staff.reports')
            ->with('status', "Report \"{$report->report_title}\" updated successfully.");
    }

    // ── Delete ───────────────────────────────────────────────────────────────

    public function destroy(Request $request, Report $report): JsonResponse|RedirectResponse
    {
        $title = $report->report_title;

        $report->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Report \"{$title}\" deleted successfully.",
            ]);
        }

        return redirect()->route('staff.reports')
            ->with('status', "Report \"{$title}\" deleted successfully.");
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Validate incoming report form data against the reports table columns.
     */
    private function validatedReportData(Request $request): array
    {
        return $request->validate([
            'report_title' => ['required', 'string', 'max:255'],
            'report_type'  => ['required', 'string', 'max:100'],
            'report_date'  => ['required', 'date'],
            'status'       => ['required', 'string', 'in:' . implode(',', Report::STATUSES)],
            'description'  => ['nullable', 'string'],
            'remarks'      => ['nullable', 'string', 'max:255'],
        ]);
    }

    /**
     * Shape a Report model into the flat array the reports blade table expects.
     */
    private function formatReport(Report $report): array
    {
        return [
            'id'          => $report->id,
            'title'       => $report->report_title,
            'type'        => $report->report_type,
            'date'        => optional($report->report_date)->format('Y-m-d'),
            'description' => $report->description,
            'status'      => $report->status,
            'remarks'     => $report->remarks,
        ];
    }
}