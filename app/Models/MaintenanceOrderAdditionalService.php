<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceOrderAdditionalService extends Model
{
    use HasFactory;

    protected $table = 'maintenance_orders_additional_services';

    protected $fillable = [
        'order_id',
        'service_id',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'service_id' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(MaintenanceOrder::class, 'order_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(MaintenanceOrder::class, 'service_id', 'id');
    }
}
