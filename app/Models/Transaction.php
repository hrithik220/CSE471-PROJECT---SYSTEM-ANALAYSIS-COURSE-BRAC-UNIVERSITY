<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'resource_id',
        'borrower_id',
        'lender_id',
        'status',
        'due_date',
        'return_date',
        'exchange_item',
        'notes',
    ];

    protected $casts = [
        'due_date'    => 'datetime',
        'return_date' => 'datetime',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    public function reminders()
    {
        return $this->hasMany(BorrowReminder::class);
    }

   public function reports()
    {
        return $this->hasMany(ResourceReport::class);
    }

    public function isOverdue(): bool
    {
        return in_array($this->status, ['active', 'overdue'])
            && $this->due_date
            && $this->due_date->isPast();
    }
}