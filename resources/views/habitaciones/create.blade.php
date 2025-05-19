@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Habitación</h2>
    <form action="{{ route('habitaciones.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="numero_habitacion" class="form-label">Número</label>
            <input type="text" name="numero_habitacion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="piso_habitacion" class="form-label">Piso</label>
            <input type="number" name="piso_habitacion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="id_tipo_habitacion" class="form-label">Tipo de Habitación</label>
            <select name="id_tipo_habitacion" class="form-select" required>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->tipo_habitacion }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_estado_habitacion" class="form-label">Estado</label>
            <select name="id_estado_habitacion" class="form-select" required>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}">{{ $estado->estado_habitacion }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Crear</button>
    </form>
</div>
@endsection
