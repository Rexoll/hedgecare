<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class jobBoardOrderAdditionalService extends Model
{
    use HasFactory;
    protected $table = 'job_board_orders_additional_services';
    protected $fillable = [
        'order_id',
        'housekeeping_id',
        'rentafriend_id',
        'maintenance_id',
    ];
    protected $hidden = [
        'order_id',
        'housekeeping_id',
        'rentafriend_id',
        'maintenance_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'order_id' => 'integer',
        'additional_service_id' => 'integer',
    ];

    public function maintenance(): HasOne
    {
        return $this->hasOne(MaintenanceAdditionalService::class, 'id', 'maintenance_id');
    }

    /**
     * Get the houseKeeping associated with the jobBoardOrderAdditionalService
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function housekeeping(): HasOne
    {
        return $this->hasOne(HousekeepingAdditionalService::class, 'id', 'housekeeping_id');
    }

    public function rentafriend(): HasOne
    {
        return $this->hasOne(rentAfriendAdditionalService::class, 'id', 'rentafriend_id');
    }

    /**
     * Get the order that owns the jobBoardOrderAdditionalService
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(jobBoardOrders::class, 'id', 'order_id');
    }
}
