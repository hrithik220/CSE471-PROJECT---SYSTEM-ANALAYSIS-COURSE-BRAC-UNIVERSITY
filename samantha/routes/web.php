<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\BorrowRequestController;

/*
|--------------------------------------------------------------------------
| Samantha – Module 1 & Module 2 Routes
|--------------------------------------------------------------------------
| All routes are protected by the 'auth' middleware.
| Role-based middleware ('role:student') can be added if needed.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // ── MODULE 1: Post / Manage Resources ────────────────────────────────────

    // Show the resource creation form
    Route::get('/resources/create', [ResourceController::class, 'create'])
        ->name('resources.create');

    // Store a new resource posting
    Route::post('/resources', [ResourceController::class, 'store'])
        ->name('resources.store');

    // Show a single resource (needed for redirect after post)
    Route::get('/resources/{resource}', [ResourceController::class, 'show'])
        ->name('resources.show');


    // ── MODULE 2: Lending & Borrowing Dashboard ───────────────────────────────

    // Main transactions dashboard
    Route::get('/dashboard/transactions', [ResourceController::class, 'dashboard'])
        ->name('dashboard');

    // Send SMS reminder for overdue item (POST to avoid accidental GET triggers)
    Route::post('/transactions/{transaction}/remind', [ResourceController::class, 'remindBorrower'])
        ->name('transactions.remind');

    // Show transaction detail
    Route::get('/transactions/{transaction}', [ResourceController::class, 'show'])
        ->name('transactions.show');

    // Mark a borrowed item as returned
    Route::patch('/transactions/{transaction}/return', [ResourceController::class, 'markReturned'])
        ->name('transactions.return');

    // Approve a pending borrow request
    Route::patch('/requests/{borrowRequest}/approve', [BorrowRequestController::class, 'approve'])
        ->name('requests.approve');

    // Reject a pending borrow request
    Route::patch('/requests/{borrowRequest}/reject', [BorrowRequestController::class, 'reject'])
        ->name('requests.reject');
});
