<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicationController;

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
