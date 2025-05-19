<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use App\Models\EstadoHabitacion;
use App\Models\Habitacion;

use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $tipos = TipoHabitacion::all();
        $estados = EstadoHabitacion::all();

        $query = Habitacion::with(['tipo', 'estado']);

        if ($request->filled('numero')) {
            $query->where('numero_habitacion', 'like', '%' . $request->numero . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('id_tipo_habitacion', $request->tipo);
        }

        if ($request->filled('estado')) {
            $query->where('id_estado_habitacion', $request->estado);
        }

        $habitaciones = $query->orderBy('numero_habitacion', 'asc')->paginate(10);


        return view('habitaciones.index', compact('habitaciones', 'tipos', 'estados'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoHabitacion::all();
        $estados = EstadoHabitacion::all();
        return view('habitaciones.create', compact('tipos', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_tipo_habitacion' => 'required|exists:tipo_habitacion,id',
            'id_estado_habitacion' => 'required|exists:estado_habitacion,id',
            'numero_habitacion' => 'required|string|max:10|unique:habitacion,numero_habitacion',
            'piso_habitacion' => 'required|integer|min:1',
        ]);

        Habitacion::create($request->all());

        return redirect()->route('habitaciones.index')->with('success', 'Habitación creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Habitacion $habitacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $tipos = TipoHabitacion::all();
        $estados = EstadoHabitacion::all();

        return view('habitaciones.edit', compact('habitacion', 'tipos', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_tipo_habitacion' => 'required|exists:tipo_habitacion,id',
            'id_estado_habitacion' => 'required|exists:estado_habitacion,id',
            'numero_habitacion' => 'required|string|max:10|unique:habitacion,numero_habitacion,' . $id,
            'piso_habitacion' => 'required|integer|min:1',
        ]);

        $habitacion = Habitacion::findOrFail($id);
        $habitacion->update($request->all());

        return redirect()->route('habitaciones.index')->with('success', 'Habitación actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habitacion $habitacion)
    {
        //
    }
}
