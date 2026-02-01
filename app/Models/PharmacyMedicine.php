<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyMedicine extends Model
{
    protected $fillable = ['pharmacy_id', 'medicine_id', 'min_stock', 'on_hand'];
}
