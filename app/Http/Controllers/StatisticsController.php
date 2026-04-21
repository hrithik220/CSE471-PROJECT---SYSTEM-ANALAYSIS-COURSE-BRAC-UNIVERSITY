<?php

namespace App\Http\Controllers;

use App\Models\UserStatistic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user()->load(['badges']);

        $totalLent = DB::table('transactions')
            ->where('lender_id', $user->id)
            ->whereIn('status', ['active', 'overdue', 'returned'])
            ->count();

        $totalBorrowed = DB::table('transactions')
            ->where('borrower_id', $user->id)
            ->whereIn('status', ['active', 'overdue', 'returned'])
            ->count();

        $positiveReviews = DB::table('reviews')
            ->where('reviewee_id', $user->id)
            ->where('rating', '>=', 4)
            ->count();

        $karmaPoints = ($totalLent * 10) + ($positiveReviews * 5);

        $stat = new UserStatistic([
            'total_items_lent'           => $totalLent,
            'total_items_borrowed'       => $totalBorrowed,
            'karma_points'               => $karmaPoints,
            'environmental_impact_score' => $totalLent * 2.5,
            'items_saved_from_purchase'  => $totalLent,
        ]);

        $monthlyLending = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->where('lender_id', $user->id)
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderBy('year')->orderBy('month')
            ->get();

        return view('statistics.index', compact('user', 'stat', 'monthlyLending'));
    }
}