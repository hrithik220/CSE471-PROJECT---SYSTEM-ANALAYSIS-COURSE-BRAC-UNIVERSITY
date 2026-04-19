<?php

namespace App\Http\Controllers;

use App\Models\BorrowReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all reminders for the authenticated user.
     */
    public function index()
    {
        $reminders = BorrowReminder::with(['borrow.resource'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('reminders.index', compact('reminders'));
    }

    /**
     * Show all database notifications (push-style) for the user.
     */
    public function notifications()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('reminders.notifications', compact('notifications'));
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(string $id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
