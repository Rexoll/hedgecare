<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreetAddress extends Model
{
    use HasFactory;

    protected $table = 'street_addresses';

    protected $fillable = [
        'name',
        'state_code',
        'country_code',
    ];

    protected $hidden = [
        'state_id',
        'country_id',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'flag',
        'wikiDataId',
    ];

    protected $casts = [
        'state_id' => 'integer',
        'country_id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
    ];
}