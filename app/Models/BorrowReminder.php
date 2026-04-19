<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowReminder extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'due_date',
        'reminder_sent_at',
        'status',
    ];

    protected $casts = [
        'due_date'          => 'datetime',
        'reminder_sent_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
