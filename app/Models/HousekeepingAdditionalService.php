<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HousekeepingAdditionalService extends Model
{
    use HasFactory;

    protected $table = 'housekeeping_additional_services';

    protected $fillable = [
        'skill_id',
        'category_id',
    ];

    protected $hidden = [
        'category_id',
        'pivot',
    ];

    protected $casts = [
        'category_id' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(HousekeepingCategory::class, 'category_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(HousekeepingAdditionalService::class, 'service_id', 'id')->with('provider');
    }

    public function price(): HasOne
    {
        return $this->hasOne(HousekeepingAdditionalServicePrices::class, 'service_id', 'id')->with('provider');
    }

    /**
     * Get the skill associated with the HousekeepingAdditionalService
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function skill(): HasOne
    {
        return $this->hasOne(Skill::class, 'id', 'skill_id');
    }
}
