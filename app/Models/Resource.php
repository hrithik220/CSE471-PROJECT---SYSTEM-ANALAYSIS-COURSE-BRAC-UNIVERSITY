<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'title',
        'description',
        'category',
        'type',           // 'free_lending' or 'exchange'
        'photo',
        'availability_start',
        'availability_end',
        'status',         // 'available', 'borrowed', 'unavailable'
        'location_lat',
        'location_lng',
        'location_name',
        'condition',
        'ocr_text',
    ];

    protected $casts = [
        'availability_start' => 'date',
        'availability_end'   => 'date',
        'location_lat'       => 'float',
        'location_lng'       => 'float',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo
            ? asset('storage/' . $this->photo)
            : asset('images/default-resource.png');
    }
}
