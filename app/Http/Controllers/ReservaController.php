<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Habitacion;
use App\Models\EstadoHabitacion;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Reserva::with('habitacion.tipo')
            ->where('estado', 'activa'); // Solo reservas activas

        if ($request->filled('dni')) {
            $query->where('dni', 'like', '%' . $request->dni . '%');
        }

        if ($request->filled('entrada_desde')) {
            $query->where('dia_entrada', '>=', $request->entrada_desde);
        }

        if ($request->filled('salida_hasta')) {
            $query->where('dia_salida', '<=', $request->salida_hasta);
        }

        $reservas = $query->orderBy('dia_entrada', 'asc')->paginate(10);

        return view('reservas.index', compact('reservas'));
    }
    /**
     * Show the form for creating a new resource.
     */
    

    public function create()
    {
        $habitaciones = Habitacion::whereHas('estado', function($q) {
            $q->where('estado_habitacion', 'Disponible');
        })
        ->with('tipo')
        ->orderBy('numero_habitacion', 'asc')
        ->get();

        return view('reservas.create', compact('habitaciones'));
    }



    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $request->validate([
            'nombre_cliente'     => 'required|string|max:100',
            'apellido_cliente'   => 'required|string|max:100',
            'telefono_cliente'   => 'nullable|string|max:20',
            'dni'                => 'required|string|max:20',
            'dia_entrada'        => 'required|date',
            'dia_salida'         => 'required|date|after_or_equal:dia_entrada',
            'id_habitacion'      => 'required|exists:habitacion,id',
        ]);

        // Crear la reserva
        $reserva = Reserva::create([
            'id_habitacion'     => $request->id_habitacion,
            'nombre_cliente'    => $request->nombre_cliente,
            'apellido_cliente'  => $request->apellido_cliente,
            'telefono_cliente'  => $request->telefono_cliente,
            'dni'               => $request->dni,
            'dia_entrada'       => $request->dia_entrada,
            'dia_salida'        => $request->dia_salida,
        ]);

        // Cambiar el estado de la habitación a "Ocupado"
        $habitacion = Habitacion::find($request->id_habitacion);
        $habitacion->id_estado_habitacion = 2; // Asume que 'Ocupado' es ID 2
        $habitacion->save();

        return redirect()->route('reservas.index')->with('success', 'Reserva creada exitosamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit($id)
    {
        $reserva = Reserva::findOrFail($id);

        $habitaciones = Habitacion::with('tipo')
            ->where('id_estado_habitacion', 1)
            ->orWhere('id', $reserva->id_habitacion)
            ->orderBy('numero_habitacion', 'asc')
            ->get();

        return view('reservas.edit', compact('reserva', 'habitaciones'));
    }

    


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_cliente'    => 'required|string|max:100',
            'apellido_cliente'  => 'required|string|max:100',
            'telefono_cliente'  => 'nullable|string|max:20',
            'dni'               => 'required|string|max:20',
            'dia_entrada'       => 'required|date',
            'dia_salida'        => 'required|date|after_or_equal:dia_entrada',
            'id_habitacion'     => 'required|exists:habitacion,id',
        ]);

        $reserva = Reserva::findOrFail($id);
        $habitacion_anterior_id = $reserva->id_habitacion;

        // Actualizar reserva
        $reserva->update($request->all());

        // Si se cambió la habitación
        if ($request->id_habitacion != $habitacion_anterior_id) {
            Habitacion::where('id', $habitacion_anterior_id)->update(['id_estado_habitacion' => 1]); // Disponible
            Habitacion::where('id', $request->id_habitacion)->update(['id_estado_habitacion' => 2]); // Ocupado
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);

        // Liberar habitación
        $habitacion = $reserva->habitacion;
        $habitacion->id_estado_habitacion = 1;
        $habitacion->save();

        // Cancelar reserva
        $reserva->estado = 'cancelada';
        $reserva->fecha_cancelacion = Carbon::today()->toDateString();// guarda fecha exacta
        $reserva->save();

        return redirect()->route('reservas.index')->with('success', 'Reserva cancelada y habitación liberada.');
    }

}
