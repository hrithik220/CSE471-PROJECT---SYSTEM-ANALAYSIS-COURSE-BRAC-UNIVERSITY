<?php

namespace App\Http\Controllers;

use App\Models\ResourceReport;
use App\Models\User;
use App\Notifications\ReportStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = ResourceReport::with(['reporter', 'borrower', 'resource', 'reviewer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('report_type', $request->type);
        }

        $reports = $query->latest()->paginate(15);

        $stats = [
            'pending'      => ResourceReport::where('status', 'pending')->count(),
            'under_review' => ResourceReport::where('status', 'under_review')->count(),
            'resolved'     => ResourceReport::where('status', 'resolved')->count(),
            'dismissed'    => ResourceReport::where('status', 'dismissed')->count(),
        ];

        return view('admin.reports.index', compact('reports', 'stats'));
    }

    public function show(ResourceReport $report)
    {
        $report->load(['reporter', 'borrower', 'resource', 'transaction', 'reviewer']);
        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, ResourceReport $report)
    {
        $validated = $request->validate([
            'status'          => 'required|in:under_review,resolved,dismissed',
            'penalty_applied' => 'required|in:none,warning,suspension,ban',
            'admin_notes'     => 'nullable|string|max:1000',
        ]);

        $report->update([
            'status'          => $validated['status'],
            'penalty_applied' => $validated['penalty_applied'],
            'admin_notes'     => $validated['admin_notes'],
            'reviewed_by'     => Auth::id(),
            'reviewed_at'     => now(),
        ]);

        if ($validated['penalty_applied'] !== 'none') {
            $this->applyPenalty($report->borrower, $validated['penalty_applied']);
        }

        $report->reporter->notify(new ReportStatusNotification($report, 'reporter'));
        $report->borrower->notify(new ReportStatusNotification($report, 'borrower'));

        return redirect()->route('admin.reports.index')
            ->with('success', "Report #{$report->id} has been updated.");
    }

    private function applyPenalty(User $user, string $penalty): void
    {
        match ($penalty) {
            'warning'    => $user->update(['warning_count' => ($user->warning_count ?? 0) + 1]),
            'suspension' => $user->update(['suspended_until' => now()->addDays(7)]),
            'ban'        => $user->update(['is_banned' => true]),
            default      => null,
        };
    }
}
