<?php

namespace App\Jobs;

use App\Models\Badge;
use App\Models\LeaderboardEntry;
use App\Models\User;
use App\Notifications\BadgeAwardedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateLeaderboard implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $month = now()->month;
        $year  = now()->year;

        $users = User::with(['statistic', 'badges'])->get();

        foreach ($users as $user) {
            $lendingCount = DB::table('transactions')
                ->where('lender_id', $user->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $positiveReviews = DB::table('reviews')
                ->where('reviewee_id', $user->id)
                ->where('rating', '>=', 4)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $communityEngagement = DB::table('resource_reports')
                ->where('reporter_id', $user->id)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->count();

            $fraudFlag   = $this->detectFraud($user->id, $lendingCount, $positiveReviews);
            $totalPoints = ($lendingCount * 10) + ($positiveReviews * 5) + ($communityEngagement * 2);

            LeaderboardEntry::updateOrCreate(
                ['user_id' => $user->id, 'month' => $month, 'year' => $year],
                [
                    'total_points'         => $totalPoints,
                    'lending_count'        => $lendingCount,
                    'positive_reviews'     => $positiveReviews,
                    'community_engagement' => $communityEngagement,
                    'fraud_flag'           => $fraudFlag,
                ]
            );
        }

        $ranked = LeaderboardEntry::where('month', $month)
            ->where('year', $year)
            ->where('fraud_flag', false)
            ->orderByDesc('total_points')
            ->get();

        foreach ($ranked as $index => $entry) {
            $entry->update(['rank' => $index + 1]);
            if ($index === 0) {
                $this->awardTopContributorBadge($entry->user_id);
            }
        }

        $this->checkAndAwardBadges();
    }

    private function detectFraud(int $userId, int $lendingCount, int $positiveReviews): bool
    {
        if ($lendingCount === 0 && $positiveReviews > 10) return true;
        if ($positiveReviews > 0 && $lendingCount > 0) {
            if (($positiveReviews / $lendingCount) > 10) return true;
        }
        return false;
    }

    private function awardTopContributorBadge(int $userId): void
    {
        $badge = Badge::where('slug', 'top-contributor')->first();
        if (!$badge) return;

        $user = User::find($userId);
        if ($user && !$user->hasBadge('top-contributor')) {
            $user->badges()->attach($badge->id, ['awarded_at' => now()]);
            $user->notify(new BadgeAwardedNotification($badge));
        }
    }

    private function checkAndAwardBadges(): void
    {
        $badges = Badge::whereNotIn('slug', ['top-contributor'])->get();
        $users  = User::with(['statistic', 'badges'])->get();

        foreach ($users as $user) {
            $karmaPoints = $user->statistic?->karma_points ?? 0;
            foreach ($badges as $badge) {
                if ($karmaPoints >= $badge->required_points && !$user->hasBadge($badge->slug)) {
                    $user->badges()->attach($badge->id, ['awarded_at' => now()]);
                    $user->notify(new BadgeAwardedNotification($badge));
                }
            }
        }
    }
}
