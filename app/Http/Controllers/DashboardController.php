<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'my_resources'      => Resource::where('owner_id', $user->id)->count(),
            'active_borrowing'  => Transaction::where('borrower_id', $user->id)->whereIn('status', ['active','overdue'])->count(),
            'active_lending'    => Transaction::where('lender_id', $user->id)->whereIn('status', ['active','overdue'])->count(),
            'pending_requests'  => Transaction::where('lender_id', $user->id)->where('status', 'pending')->count(),
        ];

        $recentResources = Resource::with('owner')
            ->available()
            ->latest()
            ->take(6)
            ->get();

        $overdueItems = Transaction::with(['resource'])
            ->where('borrower_id', $user->id)
            ->where('status', 'overdue')
            ->get();

        return view('dashboard', compact('stats', 'recentResources', 'overdueItems'));
    }
}
