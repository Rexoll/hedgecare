<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MaintenanceAdditionalService extends Model
{
    use HasFactory;
    protected $table = 'maintenance_additional_services';

    protected $fillable = [
        'name',
        'category_id',
    ];

    protected $hidden = [
        'category_id',
        'pivot',
    ];

    protected $casts = [
        'category_id' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(MaintenanceCategory::class, 'category_id', 'id');
    }
    public function provider(): BelongsTo
    {
        return $this->belongsTo(MaintenanceAdditionalService::class, 'service_id', 'id')->with('provider');
    }

    public function price(): HasOne
    {
        return $this->hasOne(MaintenanceAdditionalServicePrice::class, 'service_id', 'id')->with('provider');
    }
}
