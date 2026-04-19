<?php

namespace App\Http\Controllers;

use App\Models\BorrowRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * BorrowRequestController
 * Handles approve / reject of incoming borrow requests from Samantha's Dashboard (Module 2).
 */
class BorrowRequestController extends Controller
{
    /**
     * Approve a pending borrow request.
     * Creates a Transaction record and notifies the requester via SMS.
     * Route: PATCH /requests/{request}/approve
     */
    public function approve(BorrowRequest $borrowRequest)
    {
        $this->authorize('update', $borrowRequest);

        if ($borrowRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        // Create an active Transaction
        $transaction = Transaction::create([
            'resource_id'  => $borrowRequest->resource_id,
            'owner_id'     => Auth::id(),
            'borrower_id'  => $borrowRequest->requester_id,
            'issued_at'    => now(),
            'due_date'     => $borrowRequest->proposed_return,
            'status'       => 'active',
        ]);

        // Mark resource as unavailable
        $borrowRequest->resource->update(['status' => 'borrowed']);

        // Update request status
        $borrowRequest->update(['status' => 'approved', 'transaction_id' => $transaction->id]);

        // SMS notification to requester
        app(\App\Services\SmsService::class)->send(
            $borrowRequest->requester->phone,
            "Great news, {$borrowRequest->requester->name}! Your borrow request for \"{$borrowRequest->resource->title}\" has been approved. Pick it up on {$borrowRequest->proposed_pickup->format('d M Y')}. – CampusShare"
        );

        return back()->with('success', 'Request approved! ' . $borrowRequest->requester->name . ' has been notified via SMS.');
    }

    /**
     * Reject a pending borrow request.
     * Route: PATCH /requests/{request}/reject
     */
    public function reject(BorrowRequest $borrowRequest)
    {
        $this->authorize('update', $borrowRequest);

        if ($borrowRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $borrowRequest->update(['status' => 'rejected']);

        // Notify requester
        app(\App\Services\SmsService::class)->send(
            $borrowRequest->requester->phone,
            "Hi {$borrowRequest->requester->name}, unfortunately your borrow request for \"{$borrowRequest->resource->title}\" was not approved this time. Browse other resources at CampusShare. – CampusShare"
        );

        return back()->with('success', 'Request rejected and requester notified.');
    }
}
