<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'image_url',
        'action_type',
        'action_value',
        'sort_order',
        'is_active',
    ];
}
