<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class tutoring extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tutoring';
    protected $fillable = [
        'order_type',
        'environment',
        'duration',
    ];

    /**
     * Get the class associated with the tutoring
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function skills(): HasMany
    {
        return $this->hasMany(selected_course::class, 'tutoring_id', 'id');
    }
}
