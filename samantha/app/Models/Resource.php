<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'category', 'condition', 'description',
        'sharing_type', 'exchange_note', 'available_from', 'available_until',
        'max_borrow_days', 'pickup_location', 'course_code', 'department',
        'tags', 'photos', 'status',
    ];

    protected $casts = [
        'tags'           => 'array',
        'photos'         => 'array',
        'available_from' => 'date',
        'available_until'=> 'date',
    ];

    // ── Relationships ──────────────────────────────

    /** The student who owns/posted this resource */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** All borrow requests made for this resource */
    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }

    /** Active/past transactions for this resource */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /** Active transaction (currently being borrowed) */
    public function activeTransaction()
    {
        return $this->hasOne(Transaction::class)->whereIn('status', ['active', 'overdue']);
    }

    // ── Scopes ─────────────────────────────────────

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByCondition($query, $condition)
    {
        return $query->where('condition', $condition);
    }
}
