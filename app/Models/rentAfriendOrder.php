<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class rentAfriendOrder extends Model
{
    use HasFactory;
    protected $table = 'rentAfriend_order';

    protected $fillable = [
        'category_id',
        'order_type',
        'service_hours',
        'detail_service',
        'provider_id',
        'start_date',
        'sub_total',
    ];

    protected $hidden = [
        'pivot',
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
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(rentAfriendAdditionalService::class, "rentAfriend_orders_additional_services", 'order_id', 'service_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(HousekeepingCategory::class, 'category_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
