<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyMedicine extends Model
{
    protected $fillable = ['pharmacy_id','medicine_id','on_hand','min_stock'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'medicine_id');
    }
}
