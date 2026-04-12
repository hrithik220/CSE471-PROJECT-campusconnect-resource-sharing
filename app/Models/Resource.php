<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'description',
        'condition', 'availability_status', 'sharing_type',
        'availability_until', 'image_paths', 'is_approved',
        'pickup_lat', 'pickup_lng', 'pickup_address',
    ];

    protected $casts = [
        'image_paths' => 'array',
        'is_approved' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(ResourceReview::class);
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeRecommended($query)
    {
        return $query->join('users', 'resources.user_id', '=', 'users.id')
                     ->orderByDesc('users.credibility_score')
                     ->orderByDesc('resources.view_count')
                     ->select('resources.*');
    }
}
