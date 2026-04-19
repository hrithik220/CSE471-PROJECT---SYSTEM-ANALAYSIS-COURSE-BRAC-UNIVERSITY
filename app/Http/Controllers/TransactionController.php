<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Feature 3: Dashboard of all transactions with status
    public function index()
    {
        $user = Auth::user();

        // Mark overdue
        Transaction::where('borrower_id', $user->id)
            ->orWhere('lender_id', $user->id)
            ->where('status', 'active')
            ->whereDate('due_date', '<', now())
            ->update(['status' => 'overdue']);

        $borrowing = Transaction::with(['resource', 'lender'])
            ->where('borrower_id', $user->id)
            ->whereIn('status', ['pending', 'approved', 'active', 'overdue'])
            ->latest()
            ->get();

        $lending = Transaction::with(['resource', 'borrower'])
            ->where('lender_id', $user->id)
            ->whereIn('status', ['pending', 'approved', 'active', 'overdue'])
            ->latest()
            ->get();

        $pendingRequests = Transaction::with(['resource', 'borrower'])
            ->where('lender_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $history = Transaction::with(['resource', 'borrower', 'lender'])
            ->where(function ($q) use ($user) {
                $q->where('borrower_id', $user->id)->orWhere('lender_id', $user->id);
            })
            ->whereIn('status', ['returned', 'rejected'])
            ->latest()
            ->take(10)
            ->get();

        return view('transactions.index', compact('borrowing', 'lending', 'pendingRequests', 'history'));
    }

    // Request to borrow a resource
    public function store(Request $request)
    {
        $request->validate([
            'resource_id'  => 'required|exists:resources,id',
            'due_date'     => 'required|date|after:today',
            'message'      => 'nullable|string|max:300',
            'exchange_item'=> 'nullable|string|max:255',
        ]);

        $resource = Resource::findOrFail($request->resource_id);

        if ($resource->owner_id === Auth::id()) {
            return back()->with('error', 'You cannot borrow your own resource.');
        }

        if ($resource->status !== 'available') {
            return back()->with('error', 'This resource is not currently available.');
        }

        Transaction::create([
            'resource_id'   => $resource->id,
            'borrower_id'   => Auth::id(),
            'lender_id'     => $resource->owner_id,
            'status'        => 'pending',
            'due_date'      => $request->due_date,
            'message'       => $request->message,
            'exchange_item' => $request->exchange_item,
        ]);

        return back()->with('success', 'Borrow request sent!');
    }

    // Lender approves request
    public function approve(Transaction $transaction)
    {
        if ($transaction->lender_id !== Auth::id()) {
            abort(403);
        }

        $transaction->update(['status' => 'active']);
        $transaction->resource->update(['status' => 'borrowed']);

        return back()->with('success', 'Request approved!');
    }

    // Lender marks as returned
    public function markReturned(Transaction $transaction)
    {
        if ($transaction->lender_id !== Auth::id()) {
            abort(403);
        }

        $transaction->update(['status' => 'returned', 'returned_at' => now()]);
        $transaction->resource->update(['status' => 'available']);

        return back()->with('success', 'Marked as returned!');
    }

    // Lender rejects request
    public function reject(Transaction $transaction)
    {
        if ($transaction->lender_id !== Auth::id()) {
            abort(403);
        }

        $transaction->update(['status' => 'rejected']);

        return back()->with('success', 'Request rejected.');
    }
}
