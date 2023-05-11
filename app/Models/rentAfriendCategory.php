<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class rentAfriendCategory extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'rentAfriend_categories';
    protected $fillable = [
        'name',
        'thumbnail'
    ];
    public function services(): HasMany
    {
        return $this->hasMany(rentAfriendAdditionalService::class, 'category_id', 'id');
    }
}
