<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year', now()->year);

        $entries = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.credibility_score',
                DB::raw('COUNT(DISTINCT t.id) as lending_count'),
                DB::raw('COUNT(DISTINCT r.id) as positive_reviews'),
                DB::raw('(COUNT(DISTINCT t.id) * 10 + COUNT(DISTINCT r.id) * 5) as total_points')
            )
            ->leftJoin('transactions as t', function($join) use ($month, $year) {
                $join->on('users.id', '=', 't.lender_id')
                    ->whereMonth('t.created_at', $month)
                    ->whereYear('t.created_at', $year);
            })
            ->leftJoin('reviews as r', function($join) use ($month, $year) {
                $join->on('users.id', '=', 'r.reviewee_id')
                    ->where('r.rating', '>=', 4)
                    ->whereMonth('r.created_at', $month)
                    ->whereYear('r.created_at', $year);
            })
            ->groupBy('users.id', 'users.name', 'users.credibility_score')
            ->orderByDesc('total_points')
            ->get()
            ->map(function($entry, $index) {
                $entry->rank = $index + 1;
                return $entry;
            });

        $myEntry = $entries->firstWhere('id', Auth::id());
        $badges  = Badge::orderBy('required_points')->get();

        $months = collect(range(1, 12))->map(fn($m) => [
            'value' => $m,
            'label' => date('F', mktime(0, 0, 0, $m, 1)),
        ]);

        return view('leaderboard.index', compact(
            'entries', 'myEntry', 'badges', 'months', 'month', 'year'
        ));
    }

    public function myBadges()
    {
        $user   = Auth::user()->load('badges');
        $badges = Badge::orderBy('required_points')->get();
        $earnedSlugs = $user->badges->pluck('slug')->toArray();

        return view('leaderboard.badges', compact('user', 'badges', 'earnedSlugs'));
    }
}