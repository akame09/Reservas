@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Habitaciones</h2>
    <a href="{{ route('habitaciones.create') }}" class="btn btn-primary mb-3">Nueva Habitación</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filtros --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="numero" class="form-control" placeholder="Buscar por número" value="{{ request('numero') }}">
        </div>
        <div class="col-md-3">
            <select name="tipo" class="form-select">
                <option value="">Todos los tipos</option>
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo->id }}" {{ request('tipo') == $tipo->id ? 'selected' : '' }}>
                        {{ $tipo->tipo_habitacion }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="estado" class="form-select">
                <option value="">Todos los estados</option>
                @foreach($estados as $estado)
                    <option value="{{ $estado->id }}" {{ request('estado') == $estado->id ? 'selected' : '' }}>
                        {{ $estado->estado_habitacion }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-outline-secondary w-100" type="submit">Filtrar</button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('habitaciones.index') }}" class="btn btn-outline-danger w-100">Limpiar</a>
        </div>
    </form>

    {{-- Cards de habitaciones responsivas (5 por fila en xl) --}}
    <style>
        @media (min-width: 1200px) {
            .card-5-col {
                width: 19%;
                margin: 0.5%;
            }
        }

        @media (min-width: 768px) and (max-width: 1199.98px) {
            .card-5-col {
                width: 32%;
                margin: 1%;
            }
        }

        @media (max-width: 767.98px) {
            .card-5-col {
                width: 48%;
                margin: 1%;
            }
        }

        @media (max-width: 575.98px) {
            .card-5-col {
                width: 100%;
                margin-bottom: 1rem;
            }
        }
    </style>

    <div class="d-flex flex-wrap justify-content-start">
        @forelse($habitaciones as $habitacion)
            @php
                $estado = strtolower($habitacion->estado->estado_habitacion);
                $bgClass = match($estado) {
                    'disponible' => 'bg-success text-white',
                    'ocupado' => 'bg-warning text-dark',
                    'en mantenimiento' => 'bg-danger text-white',
                    default => 'bg-secondary text-white',
                };
            @endphp

            {{-- Card de habitación --}}

            <div class="card card-5-col {{ $bgClass }}">
                <div class="card-body">
                    <h5 class="card-title">Habitación {{ $habitacion->numero_habitacion }}</h5>
                    <p class="card-text">
                        <strong>Piso:</strong> {{ $habitacion->piso_habitacion }}<br>
                        <strong>Tipo:</strong> {{ $habitacion->tipo->tipo_habitacion }}<br>
                        <strong>Precio:</strong> S/. {{ number_format($habitacion->tipo->precio, 2) }}<br>
                        <strong>Estado:</strong> {{ $habitacion->estado->estado_habitacion }}
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-between bg-white">
                    <a href="{{ route('habitaciones.edit', $habitacion->id) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form action="{{ route('habitaciones.destroy', $habitacion->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta habitación?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="w-100 text-center">
                <div class="alert alert-info">No hay habitaciones registradas.</div>
            </div>
        @endforelse
    </div>



    {{-- Paginación --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $habitaciones->withQueryString()->links() }}
    </div>
    @php
        $ruta = storage_path('app/ultima-actualizacion.txt');
        $ultimaActualizacion = file_exists($ruta) ? file_get_contents($ruta) : null;

        $esAntigua = false;

        if ($ultimaActualizacion) {
            $esAntigua = \Carbon\Carbon::parse($ultimaActualizacion)->lt(\Carbon\Carbon::now()->subDay());
        }
    @endphp

    <div class="mt-0 text-end small {{ $esAntigua ? 'text-danger' : 'text-muted' }}">
        🕒 Última actualización del estado: {{ $ultimaActualizacion ?? 'No disponible' }}
    </div>

@endsection
