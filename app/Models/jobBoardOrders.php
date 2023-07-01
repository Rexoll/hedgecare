<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class jobBoardOrders extends Model
{
    use HasFactory;
    protected $table = 'job_board_orders';
    protected $fillable = [
        'id',
        'user_id',
        'from_hour',
        'to_hour',
        'service_name',
        'street_address',
        'detail_address',
        'start_date',
        'detail_service',
        'status',
        'sub_total',
        'tax',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'pay_with_paypal',
        'pay_with_card',
        'rating',
    ];
    protected $hidden = [
        'deleted_at',
        'updated_at'
    ];

    /**
     * Get the user associated with the jobBoardOrderAdditionalService
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_Id');
    }
}
