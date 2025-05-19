<?php

namespace App\Models;
use App\Models\Habitacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    public $timestamps = false;

    protected $table = 'tipo_habitacion';

    protected $fillable = [
        'tipo_habitacion'
    ];
}


