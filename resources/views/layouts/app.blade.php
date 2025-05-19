<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administracion Hotel JK</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <style>
        body {
            min-height: 100vh;
            display: flex;
            margin: 0;
        }

        .sidebar {
            width: 220px;
            background-color: #343a40;
            color: white;
            position: relative;
            padding-bottom: 60px; /* espacio para el footer del menú */
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 15px 20px;
            background-color: #212529;
            font-size: 14px;
        }

        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
    @auth
        <div class="sidebar">
            <h4 class="p-3">Hotel JK</h4>
            <a href="{{ route('reservas.index') }}">Reservas</a>
            <a href="{{ route('habitaciones.index') }}">Habitaciones</a>
            <a href="{{ route('reportes.index') }}">Reportes</a>

            <div class="sidebar-footer">
                {{ Auth::user()->nombre }}
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-sm btn-light w-100" type="submit">Cerrar Sesión</button>
                </form>
            </div>
        </div>
    @endauth

    <div class="content">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>