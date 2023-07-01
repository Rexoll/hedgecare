<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class jobBoardOrderAdditionalService extends Model
{
    use HasFactory;
    protected $table = 'job_board_orders_additional_services';
    protected $fillable = [
        'order_id',
        'houseKeeping_id',
        'rentAfriend_id',
        'maintenance_id',
    ];
    protected $hidden = [
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
    public function houseKeeping(): HasOne
    {
        return $this->hasOne(HousekeepingAdditionalService::class, 'id', 'houseKeeping_id');
    }

    public function rentAfriend(): HasOne
    {
        return $this->hasOne(rentAfriendAdditionalService::class, 'id', 'rentAfriend_id');
    }

}
