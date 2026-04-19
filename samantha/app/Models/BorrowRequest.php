<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id', 'requester_id', 'proposed_pickup',
        'proposed_return', 'message', 'status', 'transaction_id',
    ];

    protected $casts = [
        // BUG FIX: Migration defines these as `date`, not `datetime`.
        // Using `datetime` caused Carbon to include time when formatting
        // and could break date comparisons. Changed to `date`.
        'proposed_pickup' => 'date',
        'proposed_return' => 'date',
    ];

    public function resource()    { return $this->belongsTo(Resource::class); }
    public function requester()   { return $this->belongsTo(User::class, 'requester_id'); }
    public function transaction() { return $this->belongsTo(Transaction::class); }
}
