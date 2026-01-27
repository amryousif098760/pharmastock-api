<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name','city'];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }
}
