<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceReport extends Model
{
    protected $fillable = [
        'reporter_id',
        'borrower_id',
        'transaction_id',
        'resource_id',
        'report_type',
        'description',
        'evidence_path',
        'status',
        'penalty_applied',
        'admin_notes',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
