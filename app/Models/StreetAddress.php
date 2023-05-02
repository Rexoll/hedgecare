<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StreetAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state_code',
        'country_code',
    ];

    protected $hidden = [
        'id',
        'state_id',
        'country_id',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'flag',
        'wikiDataId',
    ];
}