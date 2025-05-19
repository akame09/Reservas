@extends('layouts.app')
@php use Carbon\Carbon; @endphp
@section('content')
<div class="container">
    <h2>Listado de Reservas</h2>

    {{-- Formulario de filtros --}}
    <form method="GET" action="{{ route('reservas.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="dni" class="form-control" placeholder="Buscar por DNI" value="{{ request('dni') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="entrada_desde" class="form-control" value="{{ request('entrada_desde') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="salida_hasta" class="form-control" value="{{ request('salida_hasta') }}">
        </div>
        <div class="col-md-1">
            <button class="btn btn-outline-secondary w-100" type="submit">Filtrar</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('reservas.index') }}" class="btn btn-outline-danger w-100">Limpiar</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Estilos personalizados para cards responsivas de 5 por fila --}}
    <style>
        @media (min-width: 1200px) {
            .card-5-col { width: 19%; margin: 0.5%; }
        }
        @media (min-width: 768px) and (max-width: 1199.98px) {
            .card-5-col { width: 32%; margin: 1%; }
        }
        @media (max-width: 767.98px) {
            .card-5-col { width: 48%; margin: 1%; }
        }
        @media (max-width: 575.98px) {
            .card-5-col { width: 100%; margin-bottom: 1rem; }
        }
    </style>

    {{-- Cards de reservas --}}
    <div class="d-flex flex-wrap justify-content-start">
        @forelse($reservas as $reserva)
            @php

                $hoy = Carbon::today();
                $entrada = Carbon::parse($reserva->dia_entrada);
                $salida = Carbon::parse($reserva->dia_salida);

                if ($reserva->estado === 'cancelada') {
                    $cardColor = 'bg-secondary text-white';
                    $estadoVisual = 'Cancelada';
                    $badgeColor = 'bg-secondary text-white';
                } elseif ($hoy->lt($entrada)) {
                    $cardColor = 'bg-light';
                    $estadoVisual = 'Pendiente';
                    $badgeColor = 'bg-warning text-dark';
                } elseif ($hoy->between($entrada, $salida)) {
                    $cardColor = 'bg-success text-white';
                    $estadoVisual = 'En curso';
                    $badgeColor = 'bg-primary text-white';
                } else {
                    $cardColor = 'bg-danger text-white';
                    $estadoVisual = 'Atrasada';
                    $badgeColor = 'bg-danger text-white';
                }

                $dias = $entrada->diffInDays($salida);
                $precio = $reserva->habitacion->tipo->precio;
                $total = $precio * $dias;
            @endphp

            <div class="card card-5-col h-100 {{ $cardColor }}">
                <div class="d-flex flex-column h-100">
                    {{-- Card Body --}}
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title text-uppercase mb-1">
                                {{ $reserva->nombre_cliente }} {{ $reserva->apellido_cliente }}
                            </h5>
                            <span class="badge {{ $badgeColor }} mb-2">{{ $estadoVisual }}</span>

                            <ul class="list-unstyled mb-2">
                                <li><strong>DNI:</strong> {{ $reserva->dni }}</li>
                                <li><strong>Teléfono:</strong> {{ $reserva->telefono_cliente }}</li>
                                <li>
                                    <strong>Habitación:</strong>
                                    {{ $reserva->habitacion->numero_habitacion }}<span class="small text-muted">({{ $reserva->habitacion->tipo->tipo_habitacion }})</span>
                                </li>
                                <li><strong>Entrada:</strong> {{ $reserva->dia_entrada }}</li>
                                <li><strong>Salida:</strong> {{ $reserva->dia_salida }}</li>
                            </ul>
                        </div>

                        <div class="bg-info bg-opacity-25 rounded p-2 text-dark mt-auto">
                            <strong>Precio:</strong> S/. {{ number_format($total, 2) }}
                            <small class="text-muted">({{ $dias }} {{ \Illuminate\Support\Str::plural('día', $dias) }})</small>
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" onsubmit="return confirm('¿Cancelar esta reserva?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="w-100 text-center">
                <div class="alert alert-info">No hay reservas registradas.</div>
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $reservas->withQueryString()->links() }}
    </div>

    {{-- Botón crear --}}
    <div class="mt-3">
        <a href="{{ route('reservas.create') }}" class="btn btn-primary">Crear nueva reserva</a>
    </div>
</div>
@endsection
