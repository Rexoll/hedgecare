<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousekeepingAdditionalServicePrices extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'service_id',
    ];

    protected $hidden = [
        'provider_id',
        'service_id',
    ];

    protected $casts = [
        'provider_id' => 'integer',
        'service_id' => 'integer',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(HousekeepingAdditionalService::class, 'service_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}