<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class rentAfriendOrder extends Model
{
    use HasFactory;
    protected $table = 'rentAfriend_orders';

    protected $fillable = [
        'category_id',
        'order_type',
        'service_hours',
        'detail_service',
        'provider_id',
        'start_date',
        'sub_total',
        'from_hour',
        'to_hour',
        'rating',
        'status'
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
        'from_hour' => 'integer',
        'to_hour' => 'integer',
        'sub_total' => 'double',
        'tax' => 'double',
        'rating' => 'integer',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(rentAfriendAdditionalService::class, "rentAfriend_orders_additional_services", 'order_id', 'service_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(rentAfriendCategory::class, 'category_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }

    public function socialmedia(): HasMany
    {
        return $this->hasMany(rentAfriendSocialMedia::class, 'order_id', 'id');
    }
}
