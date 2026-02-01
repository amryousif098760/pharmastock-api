<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $fillable = ['warehouse_id', 'category_id', 'name', 'price', 'qty', 'image_url'];
}
