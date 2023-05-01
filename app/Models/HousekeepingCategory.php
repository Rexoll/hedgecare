<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HousekeepingCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(HousekeepingAdditionalService::class, 'category_id', 'id');
    }
}