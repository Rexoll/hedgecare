<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class tutoring extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tutoring';
    protected $fillable = [
        'registration_type',
        'course',
        'date',
        'hours',
    ];

    /**
     * Get the class associated with the tutoring
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function course(): HasOne
    {
        return $this->hasOne(course::class, 'id', 'course');
    }
}
