<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HousekeepingOrder extends Model
{
    use HasFactory;

    protected $table = 'housekeeping_orders';

    protected $fillable = [
        'category_id',
        'order_type',
        'street_address_id',
        'detail_address',
        'service_hours',
        'detail_service',
        'provider_id',
        'start_date',
    ];

    protected $hidden = [
        'pivot',
        'category_id',
        'street_address_id',
        'provider_id'
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(HousekeepingAdditionalService::class, "housekeeping_orders_additional_services", 'order_id', 'service_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(HousekeepingCategory::class, 'category_id', 'id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(StreetAddress::class, 'street_address_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}