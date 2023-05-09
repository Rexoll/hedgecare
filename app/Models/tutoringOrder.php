<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class tutoringOrder extends Model
{
    use HasFactory;
    protected $table = 'tutoring_orders';
    protected $fillable = [
        'order_type',
        'environment',
        'street_address',
        'detail_address',
        'session',
        'start_date',
        'tutoring_hours',
        'provider_id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'pay_with_paypal',
        'pay_with_card',
        'provider_id'
    ];

    protected $casts = [
        'provider_id' => 'integer',
        'start_date' => 'datetime',
    ];

    /**
     * Get the class associated with the tutoring
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'selected_course', 'tutoring_order_id', 'skill_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(provider::class, 'provider_id', 'id');
    }
}
