<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rentAfriend_contacts extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'rentAfriend_contacts';
    protected $fillable = [
        'platform',
        'username'
    ];
}
