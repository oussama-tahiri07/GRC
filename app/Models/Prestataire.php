<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Prestataire extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public $timestamps = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guard = 'prestataire';
}
