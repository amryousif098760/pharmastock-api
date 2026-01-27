<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nonce extends Model
{
    protected $fillable = ['app_id','nonce','ts'];
}
