<?php

use Illuminate\Support\Facades\Route;
use App\Models\Reserva;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');

    
});

    Route::middleware('auth')->group(function () {
        Route::get('/', fn() => redirect()->route('reservas.index'));
        Route::resource('reservas', ReservaController::class);
        Route::resource('habitaciones', HabitacionController::class);
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        // web.php
        Route::get('/reportes/graficos', [ReporteController::class, 'graficos'])->name('reportes.graficos');


    });

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');