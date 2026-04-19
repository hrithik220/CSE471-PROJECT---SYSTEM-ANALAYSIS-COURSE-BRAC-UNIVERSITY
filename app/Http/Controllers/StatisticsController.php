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
        $user = Auth::user()->load(['statistic', 'badges']);

        $stat = $user->statistic ?? new UserStatistic([
            'total_items_lent'           => 0,
            'total_items_borrowed'       => 0,
            'karma_points'               => 0,
            'environmental_impact_score' => 0,
            'items_saved_from_purchase'  => 0,
        ]);

        // Monthly trend - last 6 months lending
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
