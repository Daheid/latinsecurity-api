<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // 🚀 Importante añadir este
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ArticleController;

// =========================================================
// RUTAS PÚBLICAS (La web de React accede libremente)
// =========================================================

// Login para obtener el token
Route::post('/login', [AuthController::class, 'login']);

// Publicaciones
Route::get('/publications', [PublicationController::class, 'index']);
Route::post('/publications/{publication}/download', [PublicationController::class, 'download']);

// Eventos
Route::get('/events', [EventController::class, 'index']);
Route::post('/events/{event}/attendances', [AttendanceController::class, 'store']); // Registrar asistencia

// Artículos (Solo lectura para el público)
Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{article}', [ArticleController::class, 'show']);


// =========================================================
// RUTAS PRIVADAS (Requieren enviar el Token por los Headers)
// =========================================================
Route::middleware('auth:sanctum')->group(function () {

    // Cerrar sesión y ver datos del admin actual
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Administración de Publicaciones
    Route::post('/publications', [PublicationController::class, 'store']);
    Route::put('/publications/{publication}', [PublicationController::class, 'update']);
    Route::delete('/publications/{publication}', [PublicationController::class, 'destroy']);

    // Administración de Eventos
    Route::post('/events', [EventController::class, 'store']);
    Route::patch('/events/{event}/ready', [EventController::class, 'markAsReady']);
    Route::get('/events/{event}/attendances', [AttendanceController::class, 'index']); // El admin lee la lista

    // Administración de Artículos (Crear, Editar, Eliminar)
    // Usamos except() porque index y show ya están en la zona pública
    Route::apiResource('articles', ArticleController::class)->except(['index', 'show']);
});
