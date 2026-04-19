<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceReview extends Model
{
    protected $fillable = ['resource_id', 'reviewer_id', 'rating', 'comment'];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
