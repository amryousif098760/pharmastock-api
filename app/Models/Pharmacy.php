<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model
{
    protected $fillable = ['user_id', 'name', 'lat', 'lng', 'address_text'];
}
