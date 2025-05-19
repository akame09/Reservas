<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // Tu tabla personalizada

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol'
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false;
}
