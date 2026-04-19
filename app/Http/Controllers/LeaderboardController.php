<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\LeaderboardEntry;
use App\Models\UserStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the monthly leaderboard.
     */
    public function index(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year  = $request->get('year',  now()->year);

        $entries = LeaderboardEntry::with('user')
            ->where('month', $month)
            ->where('year',  $year)
            ->where('fraud_flag', false)
            ->orderBy('rank')
            ->paginate(20);

        $myEntry = LeaderboardEntry::where('user_id', Auth::id())
            ->where('month', $month)
            ->where('year',  $year)
            ->first();

        $badges = Badge::orderBy('required_points')->get();

        $months = collect(range(1, 12))->map(fn($m) => [
            'value' => $m,
            'label' => date('F', mktime(0, 0, 0, $m, 1)),
        ]);

        return view('leaderboard.index', compact(
            'entries', 'myEntry', 'badges', 'months', 'month', 'year'
        ));
    }

    /**
     * Show all badges a user has earned.
     */
    public function myBadges()
    {
        $user   = Auth::user()->load('badges');
        $badges = Badge::orderBy('required_points')->get();

        $earnedSlugs = $user->badges->pluck('slug')->toArray();

        return view('leaderboard.badges', compact('user', 'badges', 'earnedSlugs'));
    }
}
