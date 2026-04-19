<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ResourceReport;
use App\Notifications\ReportStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        $transaction = null;
        if ($request->has('transaction_id')) {
            $transaction = Transaction::with('resource')
                ->where('lender_id', Auth::id())
                ->findOrFail($request->transaction_id);
        }

        return view('reports.create', compact('transaction'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'report_type'    => 'required|in:lost,damaged,unreturned',
            'description'    => 'required|string|min:20|max:1000',
            'evidence'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $transaction = Transaction::with(['borrower', 'resource'])->findOrFail($validated['transaction_id']);

        if ($transaction->lender_id !== Auth::id()) {
            abort(403, 'You are not authorized to report this transaction.');
        }

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('reports/evidence', 'public');
        }

        $report = ResourceReport::create([
            'reporter_id'    => Auth::id(),
            'borrower_id'    => $transaction->borrower_id,
            'transaction_id' => $transaction->id,
            'resource_id'    => $transaction->resource_id,
            'report_type'    => $validated['report_type'],
            'description'    => $validated['description'],
            'evidence_path'  => $evidencePath,
            'status'         => 'pending',
        ]);

        $transaction->borrower->notify(new ReportStatusNotification($report, 'borrower'));

        return redirect()->route('reports.my')
            ->with('success', 'Report submitted successfully. Admin will review it shortly.');
    }

    public function myReports()
    {
        $reports = ResourceReport::with(['resource', 'borrower'])
            ->where('reporter_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('reports.my', compact('reports'));
    }

    public function show(ResourceReport $report)
    {
        if (!in_array(Auth::id(), [$report->reporter_id, $report->borrower_id])
            && Auth::user()->role !== 'admin') {
            abort(403);
        }

        return view('reports.show', compact('report'));
    }
}
