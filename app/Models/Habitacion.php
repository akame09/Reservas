<?php

namespace App\Models;
use App\Models\TipoHabitacion;
use App\Models\EstadoHabitacion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    public $timestamps = false;

    protected $table = 'habitacion';

    protected $fillable = [
        'id_tipo_habitacion',
        'id_estado_habitacion',
        'numero_habitacion',
        'piso_habitacion'
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoHabitacion::class, 'id_tipo_habitacion');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoHabitacion::class, 'id_estado_habitacion');
    }
}
