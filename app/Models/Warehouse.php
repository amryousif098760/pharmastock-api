<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = ['name', 'lat', 'lng', 'address_text'];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class, 'warehouse_id');
    }
}
