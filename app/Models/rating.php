<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class rating extends Model
{
    use HasFactory;
    protected $table = 'ratings';
    protected $fillable = [
        'provider_id',
        'ratings',
    ];

    /**
     * Get the provider associated with the rating
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(Provider::class, 'id', 'provider_id');
    }
}
