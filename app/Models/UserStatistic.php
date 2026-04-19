<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'total_items_lent', 'total_items_borrowed',
        'karma_points', 'environmental_impact_score', 'items_saved_from_purchase',
    ];

    protected $casts = [
        'environmental_impact_score' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function incrementLent(): void
    {
        $this->increment('total_items_lent');
        $this->increment('items_saved_from_purchase');
        $this->increment('karma_points', 10);
        $this->increment('environmental_impact_score', 2.5);
        $this->save();
    }

    public function incrementBorrowed(): void
    {
        $this->increment('total_items_borrowed');
        $this->save();
    }

    public function addKarmaPoints(int $points): void
    {
        $this->increment('karma_points', $points);
        $this->save();
    }
}
