<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HousekeepingAdditionalService extends Model
{
    use HasFactory;

    protected $table = 'housekeeping_additional_services';

    protected $fillable = [
        'name',
        'category_id',
    ];

    protected $hidden = [
        'category_id',
        'pivot',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(HousekeepingCategory::class, 'category_id', 'id');
    }
}