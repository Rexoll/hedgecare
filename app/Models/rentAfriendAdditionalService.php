<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class rentAfriendAdditionalService extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'rentAfriend_additional_services';
    protected $fillable = [
        'name',
        'category_id'
    ];

    protected $casts = [
        'category_id' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(rentAfriendCategory::class, 'category_id', 'id');
    }
    public function provider(): BelongsTo
    {
        return $this->belongsTo(rentAfriendAdditionalService::class, 'service_id', 'id')->with('provider');
    }

    public function price(): HasOne
    {
        return $this->hasOne(rentAfriendAdditionalServicePrice::class, 'service_id', 'id')->with('provider');
    }
}
