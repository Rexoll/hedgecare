<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomOrder extends Model
{
    use HasFactory;

    protected $table = 'custom_orders';

    protected $fillable = [
        'street_address',
        'detail_address',
        'service_hours',
        'detail_service',
        'provider_id',
        'start_date',
        'sub_total',
        'from_hour',
        'to_hour',
        'review'
    ];

    protected $hidden = [
        'provider_id',
        'pay_with_paypal',
        'pay_with_card',
        'pivot',
    ];

    protected $casts = [
        'provider_id' => 'integer',
        'start_date' => 'datetime',
        'from_hour' => 'integer',
        'to_hour' => 'integer',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'id');
    }
}
