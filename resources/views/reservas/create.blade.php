

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Reserva</h2>

    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf

        {{-- Cliente --}}
        <div class="mb-3">
            <label for="nombre_cliente" class="form-label">Nombre</label>
            <input type="text" name="nombre_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="apellido_cliente" class="form-label">Apellido</label>
            <input type="text" name="apellido_cliente" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="telefono_cliente" class="form-label">Teléfono</label>
            <input type="text" name="telefono_cliente" class="form-control">
        </div>

        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" required>
        </div>

        {{-- Fechas --}}
        <div class="mb-3">
            <label for="dia_entrada" class="form-label">Día de Entrada</label>
            <input type="date" name="dia_entrada" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="dia_salida" class="form-label">Día de Salida</label>
            <input type="date" name="dia_salida" class="form-control" required>
        </div>

        {{-- Habitación --}}
        <div class="mb-3">
            <label for="id_habitacion" class="form-label">Habitación Disponible</label>
            <select name="id_habitacion" class="form-control" required>
                <option value="">Seleccione una habitación</option>
                @foreach($habitaciones as $habitacion)
                    <option value="{{ $habitacion->id }}">
                        Habitación {{ $habitacion->numero_habitacion }} - Piso {{ $habitacion->piso_habitacion }} ({{ $habitacion->tipo->tipo_habitacion }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Reserva</button>
    </form>
</div>
@endsection
