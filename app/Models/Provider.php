<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'address',
        'category',
        'active_days',
    ];

    protected $hidden = [
        'user_id',
        'latitude',
        'longitude',
        'pivot',
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

    public function services(): HasMany
    {
        return $this->hasMany(HousekeepingAdditionalServicePrices::class, 'provider_id', 'id')->with('service');
    }

}