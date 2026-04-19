<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id', 'owner_id', 'borrower_id',
        'issued_at', 'due_date', 'returned_at', 'status',
    ];

    protected $casts = [
        'issued_at'   => 'datetime',
        'due_date'    => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function resource()  { return $this->belongsTo(Resource::class); }
    public function owner()     { return $this->belongsTo(User::class, 'owner_id'); }
    public function borrower()  { return $this->belongsTo(User::class, 'borrower_id'); }
}
// BUG FIX: BorrowRequest class was incorrectly placed here.
// It has been moved to its own file: app/Models/BorrowRequest.php
