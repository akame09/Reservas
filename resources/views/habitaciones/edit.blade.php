@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Habitación</h2>
    <form action="{{ route('habitaciones.update', $habitacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="numero_habitacion" class="form-label">Número</label>
            <input type="text" name="numero_habitacion" class="form-control" value="{{ $habitacion->numero_habitacion }}" required>
        </div>

        <div class="mb-3">
            <label for="piso_habitacion" class="form-label">Piso</label>
            <input type="number" name="piso_habitacion" class="form-control" value="{{ $habitacion->piso_habitacion }}" required>
        </div>

        <div class="mb-3">
            <label for="id_tipo_habitacion" class="form-label">Tipo de Habitación</label>
            <select name="id_tipo_habitacion" class="form-select" required>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ $habitacion->id_tipo_habitacion == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->tipo_habitacion }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="id_estado_habitacion" class="form-label">Estado</label>
            <select name="id_estado_habitacion" class="form-select" required>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}" {{ $habitacion->id_estado_habitacion == $estado->id ? 'selected' : '' }}>
                        {{ $estado->estado_habitacion }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>
@endsection
