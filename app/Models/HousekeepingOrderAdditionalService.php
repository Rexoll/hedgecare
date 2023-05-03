<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HousekeepingOrderAdditionalService extends Model
{
    use HasFactory;

    protected $table = 'housekeeping_orders_additional_services';

    protected $fillable = [
        'order_id',
        'service_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(HousekeepingOrder::class, 'order_id', 'id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(HousekeepingOrder::class, 'service_id', 'id');
    }
}