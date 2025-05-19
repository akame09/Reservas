<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoHabitacion extends Model
{
    public $timestamps = false;

    protected $table = 'estado_habitacion';

    protected $fillable = [
        'estado_habitacion'
    ];
}
