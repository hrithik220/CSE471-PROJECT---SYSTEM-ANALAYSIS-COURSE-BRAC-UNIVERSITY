<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\BorrowRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * ResourceController
 * Handles Samantha's Module 1 (Post Resource) and Module 2 (Dashboard).
 */
class ResourceController extends Controller
{
    /**
     * Show a single resource.
     * Route: GET /resources/{resource}  (resources.show)
     * Route: GET /transactions/{transaction}  (transactions.show)
     *
     * BUG FIX: This method was missing entirely, causing a BadMethodCallException
     * on redirect after store(), and a 500 error on the transactions.show route.
     */
    public function show($id)
    {
        // Works for both Resource and Transaction route bindings
        $resource = Resource::with(['owner', 'activeTransaction.borrower'])->findOrFail($id);
        return view('samantha.post-resource', compact('resource'));
    }

    // ─────────────────────────────────────────────
    //  MODULE 1 — Post Resource
    // ─────────────────────────────────────────────

    /**
     * Show the "Post Resource" form.
     * Route: GET /resources/create
     */
    public function create()
    {
        return view('samantha.post-resource');
    }

    /**
     * Store a newly posted resource.
     * Route: POST /resources
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:120',
            'category'         => 'required|in:textbook,notes,lab_equipment,electronics,stationery,other',
            'condition'        => 'required|in:new,good,fair,poor',
            'description'      => 'required|string|max:800',
            'sharing_type'     => 'required|in:free_lending,exchange,both',
            'exchange_note'    => 'nullable|string|max:200',
            'available_from'   => 'required|date|after_or_equal:today',
            'available_until'  => 'required|date|after:available_from',
            'max_borrow_days'  => 'nullable|integer|min:1|max:180',
            'pickup_location'  => 'nullable|string|max:200',
            'course_code'      => 'nullable|string|max:30',
            'department'       => 'nullable|string|max:100',
            'tags'             => 'nullable|string|max:200',
            'photos.*'         => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        // Handle photo uploads (up to 6)
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach (array_slice($request->file('photos'), 0, 6) as $photo) {
                $photoPaths[] = $photo->store('resources/photos', 'public');
            }
        }

        // Parse tags
        $tags = [];
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $tags = array_filter($tags);
        }

        $resource = Resource::create([
            'user_id'          => Auth::id(),
            'title'            => $validated['title'],
            'category'         => $validated['category'],
            'condition'        => $validated['condition'],
            'description'      => $validated['description'],
            'sharing_type'     => $validated['sharing_type'],
            'exchange_note'    => $validated['exchange_note'] ?? null,
            'available_from'   => $validated['available_from'],
            'available_until'  => $validated['available_until'],
            'max_borrow_days'  => $validated['max_borrow_days'] ?? 7,
            'pickup_location'  => $validated['pickup_location'] ?? null,
            'course_code'      => $validated['course_code'] ?? null,
            'department'       => $validated['department'] ?? null,
            'tags'             => $tags,
            'photos'           => $photoPaths,
            'status'           => 'available',
        ]);

        return redirect()->route('resources.show', $resource->id)
            ->with('success', 'Your resource has been posted successfully!');
    }

    // ─────────────────────────────────────────────
    //  MODULE 2 — Lending & Borrowing Dashboard
    // ─────────────────────────────────────────────

    /**
     * Show the lending/borrowing dashboard.
     * Route: GET /dashboard/transactions
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Items the authenticated user is lending out (active)
        $activelendings = Transaction::with(['resource', 'borrower'])
            ->where('owner_id', $user->id)
            ->whereIn('status', ['active', 'overdue'])
            ->orderByRaw("CASE WHEN due_date < NOW() THEN 0 ELSE 1 END") // overdue first
            ->orderBy('due_date')
            ->get();

        // Items the authenticated user is borrowing (active)
        $activeBorrowings = Transaction::with(['resource', 'owner'])
            ->where('borrower_id', $user->id)
            ->whereIn('status', ['active', 'overdue'])
            ->orderBy('due_date')
            ->get();

        // Incoming borrow requests on user's resources
        $pendingRequests = BorrowRequest::with(['resource', 'requester'])
            ->whereHas('resource', fn($q) => $q->where('user_id', $user->id))
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        // Completed transactions (history)
        $history = Transaction::with(['resource'])
            ->where(fn($q) => $q->where('owner_id', $user->id)->orWhere('borrower_id', $user->id))
            ->where('status', 'returned')
            ->orderBy('returned_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($t) use ($user) {
                // Attach the "other party" based on whether user is owner or borrower
                $t->type = $t->owner_id === $user->id ? 'Lending' : 'Borrowing';
                $t->otherParty = $t->owner_id === $user->id ? $t->borrower : $t->owner;
                return $t;
            });

        // Summary stats
        $stats = [
            'active_lendings'  => $activelendings->where('status', 'active')->count(),
            'active_borrowings'=> $activeBorrowings->where('status', 'active')->count(),
            'pending_requests' => $pendingRequests->count(),
            'overdue'          => $activelendings->where('status', 'overdue')->count()
                                + $activeBorrowings->where('status', 'overdue')->count(),
        ];

        return view('samantha.dashboard', compact(
            'activelendings',
            'activeBorrowings',
            'pendingRequests',
            'history',
            'stats'
        ));
    }

    /**
     * Send SMS reminder for an overdue item.
     * Route: POST /transactions/{transaction}/remind
     */
    public function remindBorrower(Transaction $transaction)
    {
        $this->authorize('update', $transaction->resource);

        // SMS API integration (see SmsService)
        app(\App\Services\SmsService::class)->send(
            $transaction->borrower->phone,
            "Hi {$transaction->borrower->name}, your borrowed item \"{$transaction->resource->title}\" from CampusShare is overdue. Please return it as soon as possible. – CampusShare"
        );

        return back()->with('success', 'SMS reminder sent to ' . $transaction->borrower->name . '.');
    }

    /**
     * Mark a borrowed item as returned.
     * Route: PATCH /transactions/{transaction}/return
     */
    public function markReturned(Transaction $transaction)
    {
        // BUG FIX: authorize('update', $transaction) was wrong — there is no
        // TransactionPolicy. The ownership check should be on the resource.
        $this->authorize('update', $transaction->resource);

        $transaction->update([
            'status'      => 'returned',
            'returned_at' => now(),
        ]);

        // Set resource back to available
        $transaction->resource->update(['status' => 'available']);

        return back()->with('success', 'Item marked as returned!');
    }
}
