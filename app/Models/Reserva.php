<?php

namespace App\Models;

use App\Models\Habitacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    
    protected $table = 'reserva';

    protected $fillable = [
        'id_habitacion',
        'nombre_cliente',
        'apellido_cliente',
        'telefono_cliente',
        'dni',
        'dia_entrada',
        'dia_salida',
    ];
    public $timestamps = false;
    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion');
    }
}