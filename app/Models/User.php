<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'campus',
        'credibility_score',
        'profile_photo',
        'warning_count',
        'suspended_until',
        'is_banned',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'suspended_until'   => 'datetime',
        'is_banned'         => 'boolean',
    ];

    // ── Module 1 & 2 relationships ─────────────────────────────────────────
    public function resources()
    {
        return $this->hasMany(Resource::class, 'owner_id');
    }

    public function borrowedTransactions()
    {
        return $this->hasMany(Transaction::class, 'borrower_id');
    }

    public function lentTransactions()
    {
        return $this->hasMany(Transaction::class, 'lender_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function updateCredibilityScore()
    {
        $avg = $this->reviewsReceived()->avg('rating') ?? 0;
        $this->update(['credibility_score' => round($avg, 1)]);
    }

    // ── Module 3 relationships ─────────────────────────────────────────────
    public function statistic()
    {
        return $this->hasOne(UserStatistic::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
                    ->withPivot('awarded_at')
                    ->withTimestamps();
    }

    public function leaderboardEntries()
    {
        return $this->hasMany(LeaderboardEntry::class);
    }

    public function resourceReports()
    {
        return $this->hasMany(ResourceReport::class, 'reporter_id');
    }

    public function borrowReminders()
    {
        return $this->hasMany(BorrowReminder::class);
    }

    public function getKarmaPointsAttribute(): int
    {
        return $this->statistic?->karma_points ?? 0;
    }

    public function hasBadge(string $slug): bool
    {
        return $this->badges()->where('slug', $slug)->exists();
    }
}
