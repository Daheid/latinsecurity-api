<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Obtener todas las publicaciones (con paginación)
Route::get('/publications', [PublicationController::class, 'index']);

// Crear una nueva publicación
Route::post('/publications', [PublicationController::class, 'store']);

// Nueva ruta para actualizar
Route::put('/publications/{publication}', [PublicationController::class, 'update']);

// Nueva ruta para eliminar
Route::delete('/publications/{publication}', [PublicationController::class, 'destroy']);

// obtener eventos 
Route::get('/events', [EventController::class, 'index']);

// Crear un nuevo evento
Route::post('/events', [EventController::class, 'store']);

// Ruta específica para cerrar el evento (usamos PATCH porque es una actualización parcial)
Route::patch('/events/{event}/ready', [EventController::class, 'markAsReady']);

// Rutas para Asistencias (Dependen de un evento específico)
// 1. Ver asistentes de un evento
Route::get('/events/{event}/attendances', [AttendanceController::class, 'index']);

// 2. Registrar asistente en un evento
Route::post('/events/{event}/attendances', [AttendanceController::class, 'store']);
