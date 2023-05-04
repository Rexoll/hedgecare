<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';

    protected $fillable = [
        'user_id',
        'thumbnail',
        'about',
        'price',
        'rating',
        'review',
        'category',
    ];

    protected $hidden = [
        'user_id',
        'start_time_available',
        'end_time_available',
        'latitude',
        'longitude',
        'pivot'
    ];

    protected $casts = [
        'price' => 'double',
        'rating' => 'double',
        'review' => 'integer',
        'user_id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}