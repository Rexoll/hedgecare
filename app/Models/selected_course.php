<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class selected_course extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'selected_course';
    protected $fillable = [
        'tutoring_order_id',
        'skills_id'
    ];

    /**
     * Get the tutoring that owns the selected_course
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tutoring(): BelongsTo
    {
        return $this->belongsTo(tutoringOrder::class, 'tutoring_order_id', 'id');
    }
}
