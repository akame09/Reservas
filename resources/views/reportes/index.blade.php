@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Reporte de Reservas</h2>
        <a href="{{ route('reportes.graficos') }}" class="btn btn-success">
            <i class="bi bi-bar-chart-fill"></i> Ver Gráficos
        </a>
    </div>
    <form method="GET" action="{{ route('reportes.index') }}" class="row g-3 mb-4">
        <div class="col-md-4">
            <label>Desde:</label>
            <input type="date" name="desde" value="{{ request('desde') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>Hasta:</label>
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control">
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Habitación</th>
                <th>Tipo</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Días</th>
                <th>Total Estimado</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse($reservas as $reserva)
                <tr>
                    <td>{{ $reserva->nombre_cliente }} {{ $reserva->apellido_cliente }}</td>
                    <td>{{ $reserva->habitacion->numero_habitacion }}</td>
                    <td>{{ $reserva->habitacion->tipo->tipo_habitacion }}</td>
                    <td>{{ $reserva->dia_entrada }}</td>
                    <td>{{ $reserva->dia_salida }}</td>
                    @php
                        $entrada = \Carbon\Carbon::parse($reserva->dia_entrada);
                        $fin = $reserva->estado === 'cancelada' && $reserva->fecha_cancelacion
                            ? \Carbon\Carbon::parse($reserva->fecha_cancelacion)
                            : \Carbon\Carbon::parse($reserva->dia_salida);

                        $dias = $entrada->diffInDays($fin);
                        $total = $dias * $reserva->habitacion->tipo->precio;
                    @endphp

                    <td>{{ $dias }}</td>
                    <td>S/. {{ number_format($total, 2) }}</td>
                    <td>
                        <span class="badge 
                            {{ 
                                $reserva->estado === 'cancelada' ? 'bg-danger' : 
                                ($reserva->estado === 'finalizada' ? 'bg-secondary' : 'bg-success') 
                            }}">
                            {{ ucfirst($reserva->estado) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">No hay reservas en el rango seleccionado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
