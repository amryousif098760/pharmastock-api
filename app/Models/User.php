<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name','email','phone','password',
        'role','approval_status',
        'email_verified_at','email_verify_token'
    ];

    protected $hidden = ['password','email_verify_token'];
}
