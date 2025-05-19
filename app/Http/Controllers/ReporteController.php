<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $query = Reserva::with(['habitacion.tipo']);

        if ($request->filled('desde')) {
            $query->where('dia_entrada', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->where('dia_salida', '<=', $request->hasta);
        }

        $reservas = $query->get();

        return view('reportes.index', compact('reservas'));
    }

    public function graficos()
    {
        // Reservas agrupadas por tipo
        $porTipo = Reserva::select(
            DB::raw('tipo_habitacion.tipo_habitacion as tipo'),
            DB::raw('COUNT(*) as total'),
            DB::raw("
                SUM(
                    DATEDIFF(
                        CASE 
                            WHEN reserva.estado = 'cancelada' THEN reserva.fecha_cancelacion
                            ELSE reserva.dia_salida
                        END,
                        reserva.dia_entrada
                    ) * tipo_habitacion.precio
                ) as ingresos
            ")
        )
        ->join('habitacion', 'reserva.id_habitacion', '=', 'habitacion.id')
        ->join('tipo_habitacion', 'habitacion.id_tipo_habitacion', '=', 'tipo_habitacion.id')
        ->groupBy('tipo_habitacion.tipo_habitacion')
        ->get();

        // Ganancias por mes (últimos 12 meses del año actual)
        $monthlyEarnings = Reserva::selectRaw("
                MONTH(dia_entrada) as month,
                SUM(
                    DATEDIFF(
                        CASE 
                            WHEN reserva.estado = 'cancelada' THEN reserva.fecha_cancelacion
                            ELSE reserva.dia_salida
                        END,
                        reserva.dia_entrada
                    ) * tipo_habitacion.precio
                ) as total
            ")
            ->join('habitacion', 'reserva.id_habitacion', '=', 'habitacion.id')
            ->join('tipo_habitacion', 'habitacion.id_tipo_habitacion', '=', 'tipo_habitacion.id')
            ->whereYear('dia_entrada', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = $monthlyEarnings->get($i, 0);
        }

        // Ganancias por año (últimos 5 años)
        $yearlyEarnings = Reserva::selectRaw("
                YEAR(dia_entrada) as year,
                SUM(
                    DATEDIFF(
                        CASE 
                            WHEN reserva.estado = 'cancelada' THEN reserva.fecha_cancelacion
                            ELSE reserva.dia_salida
                        END,
                        reserva.dia_entrada
                    ) * tipo_habitacion.precio
                ) as total
            ")
            ->join('habitacion', 'reserva.id_habitacion', '=', 'habitacion.id')
            ->join('tipo_habitacion', 'habitacion.id_tipo_habitacion', '=', 'tipo_habitacion.id')
            ->whereYear('dia_entrada', '>=', now()->year - 4)
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year');

        // Ganancia total del año actual
        $totalCurrentYear = Reserva::join('habitacion', 'reserva.id_habitacion', '=', 'habitacion.id')
            ->join('tipo_habitacion', 'habitacion.id_tipo_habitacion', '=', 'tipo_habitacion.id')
            ->whereYear('dia_entrada', now()->year)
            ->sum(DB::raw("
                DATEDIFF(
                    CASE 
                        WHEN reserva.estado = 'cancelada' THEN reserva.fecha_cancelacion
                        ELSE reserva.dia_salida
                    END,
                    reserva.dia_entrada
                ) * tipo_habitacion.precio
            "));

        return view('reportes.graficos', compact('porTipo', 'months', 'yearlyEarnings', 'totalCurrentYear'));
    }
}
