@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard de Ganancias</h2>

    {{-- Tarjeta de Ganancia Anual --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Ganancia Total del Año {{ now()->year }}</h5>
                    <h3 class="card-text">S/. {{ number_format($totalCurrentYear, 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <canvas id="monthlyEarningsChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="yearlyEarningsChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <canvas id="reservasChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="ingresosChart"></canvas>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // === Reservas por tipo (Barra) ===
    const tipos = {!! json_encode($porTipo->pluck('tipo')) !!};
    const cantidades = {!! json_encode($porTipo->pluck('total')) !!};
    const ingresos = {!! json_encode($porTipo->pluck('ingresos')) !!};

    new Chart(document.getElementById('reservasChart'), {
        type: 'bar',
        data: {
            labels: tipos,
            datasets: [{
                label: 'Cantidad de Reservas',
                data: cantidades,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Reservas por Tipo de Habitación' }
            }
        }
    });

    // === Ingresos por tipo (Pie) ===
    new Chart(document.getElementById('ingresosChart'), {
        type: 'pie',
        data: {
            labels: tipos,
            datasets: [{
                label: 'Ingresos',
                data: ingresos,
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Ingresos por Tipo de Habitación' }
            }
        }
    });

    // === Ganancias por mes (Barra) ===
    const monthlyLabels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
    const monthlyData = {!! json_encode($months) !!};

    new Chart(document.getElementById('monthlyEarningsChart'), {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Ganancias Mensuales (S/.)',
                data: monthlyData,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Ganancias por Mes' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // === Ganancias por año (Línea) ===
    const yearlyLabels = {!! json_encode($yearlyEarnings->keys()) !!};
    const yearlyData = {!! json_encode($yearlyEarnings->values()) !!};

    new Chart(document.getElementById('yearlyEarningsChart'), {
        type: 'line',
        data: {
            labels: yearlyLabels,
            datasets: [{
                label: 'Ganancias Anuales (S/.)',
                data: yearlyData,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Ganancias por Año' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
