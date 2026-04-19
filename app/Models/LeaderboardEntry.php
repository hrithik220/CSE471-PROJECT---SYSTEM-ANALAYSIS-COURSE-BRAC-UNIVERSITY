<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'month', 'year', 'total_points',
        'lending_count', 'positive_reviews', 'community_engagement',
        'rank', 'fraud_flag',
    ];

    protected $casts = [
        'fraud_flag' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCurrentMonth($query)
    {
        return $query->where('month', now()->month)->where('year', now()->year);
    }

    public function scopeNotFlagged($query)
    {
        return $query->where('fraud_flag', false);
    }
}
