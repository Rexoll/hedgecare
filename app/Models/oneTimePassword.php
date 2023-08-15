<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oneTimePassword extends Model
{
    use HasFactory;
    protected $table = 'one_time_passwords';
    protected $fillable = [
        'one_time_password',
        'user_id',
        'expired_at'
    ];
}
