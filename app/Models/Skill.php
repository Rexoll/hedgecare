<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = [
        'name',
        'thumbnail',
    ];

    protected $hidden = [
        'pivot'
    ];
    public function Providers(): BelongsToMany
    {
        return $this->belongsToMany(Provider::class);
    }
}