<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceAdditionalServicePrice extends Model
{
    use HasFactory;

    protected $table = 'maintenance_additional_service_prices';

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
        return $this->belongsTo(MaintenanceAdditionalService::class, 'service_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
