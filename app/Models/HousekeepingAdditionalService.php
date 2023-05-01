<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousekeepingAdditionalService extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
    ];

    protected $hidden = [
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(HousekeepingCategory::class, 'category_id', 'id');
    }
}