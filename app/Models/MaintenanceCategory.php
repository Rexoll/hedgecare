<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceCategory extends Model
{
    use HasFactory;

    protected $table = 'maintenance_categories';


    protected $fillable = [
        'name',
        'thumbnail',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(MaintenanceAdditionalService::class, 'category_id', 'id');
    }
}
