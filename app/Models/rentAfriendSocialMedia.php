<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rentAfriendSocialMedia extends Model
{
    use HasFactory;
    protected $table = 'rentAfriend_social_media';
    protected $fillable = [
        'order_id',
        'platform',
        'username'
    ];
}
