<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MaintenanceOrder extends Model
{
    use HasFactory;

    protected $table = 'maintenance_orders';

    protected $fillable = [
        'category_id',
        'street_address',
        'detail_address',
        'service_hours',
        'detail_service',
        'provider_id',
        'sub_total',
        'start_date',
        'from_hour',
        'expected_hour',
        'user_id',
        'rating',
        'tax',
        'status'
    ];

    protected $hidden = [
        'category_id',
        'provider_id',
        'pay_with_paypal',
        'pay_with_card',
        'pivot',
    ];

    protected $casts = [
        'category_id' => 'integer',
        'provider_id' => 'integer',
        'start_date' => 'datetime',
        'from_hour' => 'integer',
        'to_hour' => 'integer',
        'sub_total' => 'double',
        'tax' => 'double',
        'rating' => 'integer',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(MaintenanceAdditionalService::class, "maintenance_orders_additional_services", 'order_id', 'service_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaintenanceCategory::class, 'category_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
