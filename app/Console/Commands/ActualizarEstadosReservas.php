<?php

namespace App\Console\Commands;
use App\Models\Reserva;
use App\Models\Habitacion;
use App\Models\EstadoHabitacion;
use App\Models\TipoHabitacion;  
use Carbon\Carbon;
use Illuminate\Console\Command;
   use Illuminate\Support\Facades\File;


class ActualizarEstadosReservas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
 
    
    protected $signature = 'actualizar:estados-reservas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoy = \Carbon\Carbon::today();

        // 1. Marcar reservas como finalizadas si ya pasaron
        \App\Models\Reserva::where('estado', 'activa')
            ->where('dia_salida', '<', $hoy)
            ->update(['estado' => 'finalizada']);

        // 2. Gestionar habitaciones de reservas finalizadas
        $reservasFinalizadas = \App\Models\Reserva::where('estado', 'finalizada')->get();

        foreach ($reservasFinalizadas as $reserva) {
            $habitacion = $reserva->habitacion;
            $fechaSalida = \Carbon\Carbon::parse($reserva->dia_salida);

            if ($hoy->toDateString() === $fechaSalida->toDateString()) {
                \Log::info("→ Habitacion {$habitacion->id} en mantenimiento por reserva finalizada");
                $habitacion->id_estado_habitacion = 3; // mantenimiento
                $habitacion->save();
            }

            if ($hoy->toDateString() >= $fechaSalida->copy()->addDay()->toDateString()) {
                if ($habitacion->id_estado_habitacion == 3) {
                    \Log::info("→ Habitacion {$habitacion->id} liberada automáticamente tras finalizada");
                    $habitacion->id_estado_habitacion = 1; // disponible
                    $habitacion->save();
                }
            }
        }

        // 3. Gestionar habitaciones de reservas canceladas
        $reservasCanceladas = \App\Models\Reserva::where('estado', 'cancelada')->get();

        foreach ($reservasCanceladas as $reserva) {
            $habitacion = $reserva->habitacion;
            $fechaCancelacion = \Carbon\Carbon::parse($reserva->fecha_cancelacion);

            \Log::info("→ Procesando habitación {$habitacion->id} con cancelación: {$fechaCancelacion->toDateString()}");

            if ($hoy->toDateString() === $fechaCancelacion->toDateString()) {
                \Log::info("→ Día de cancelación: habitación {$habitacion->id} en mantenimiento");
                $habitacion->id_estado_habitacion = 3;
                $habitacion->save();
            }

            if ($hoy->toDateString() >= $fechaCancelacion->copy()->addDay()->toDateString()) {
                if ($habitacion->id_estado_habitacion == 3) {
                    \Log::info("→ Día siguiente: habitación {$habitacion->id} vuelve a disponible");
                    $habitacion->id_estado_habitacion = 1;
                    $habitacion->save();
                }
            }
        }

        // 4. Registrar fecha de última ejecución
        \Illuminate\Support\Facades\File::put(
            storage_path('app/ultima-actualizacion.txt'),
            now()->toDateTimeString()
        );

        $this->info('Comando ejecutado correctamente. Reservas finalizadas y habitaciones actualizadas.');
    }


}
