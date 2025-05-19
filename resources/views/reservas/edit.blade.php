@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Reserva</h2>

    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Cliente --}}
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre_cliente" value="{{ $reserva->nombre_cliente }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido_cliente" value="{{ $reserva->apellido_cliente }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono_cliente" value="{{ $reserva->telefono_cliente }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">DNI</label>
            <input type="text" name="dni" value="{{ $reserva->dni }}" class="form-control" required>
        </div>

        {{-- Fechas --}}
        <div class="mb-3">
            <label class="form-label">Día de Entrada</label>
            <input type="date" name="dia_entrada" value="{{ $reserva->dia_entrada }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Día de Salida</label>
            <input type="date" name="dia_salida" value="{{ $reserva->dia_salida }}" class="form-control" required>
        </div>

        {{-- Habitación --}}
        <div class="mb-3">
            <label class="form-label">Habitación</label>
            <select name="id_habitacion" class="form-control" required>
                @foreach($habitaciones as $habitacion)
                    <option value="{{ $habitacion->id }}" {{ $habitacion->id == $reserva->id_habitacion ? 'selected' : '' }}>
                        Hab. {{ $habitacion->numero_habitacion }} - Piso {{ $habitacion->piso_habitacion }} ({{ $habitacion->tipo->tipo_habitacion }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Reserva</button>
    </form>
</div>
@endsection
