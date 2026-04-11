<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\CompleteEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    /**
     * Obtener todos los eventos (opcional: filtrar por estado)
     */
    public function index(): JsonResponse
    {
        // Puedes pasar ?status=active en la URL para ver solo los activos
        $status = request('status');

        $events = Event::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->latest('date')->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $events
        ]);
    }

    /**
     * Crear un nuevo evento
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = Event::create($request->validated()); // Por defecto status será 'active'

        return response()->json([
            'success' => true,
            'message' => 'Evento creado exitosamente.',
            'data'    => $event
        ], 201);
    }

    /**
     * Marcar un evento como "ready" (listo) y añadir resultados
     */
    public function markAsReady(CompleteEventRequest $request, Event $event): JsonResponse
    {
        // Verificamos que no esté cerrado ya
        if ($event->status === 'ready') {
            return response()->json([
                'success' => false,
                'message' => 'Este evento ya fue marcado como listo anteriormente.'
            ], 400);
        }

        // Actualizamos el estado y guardamos el resultado/implementación
        $event->update([
            'status'         => 'ready',
            'result'         => $request->result,
            'implementation' => $request->implementation,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Evento marcado como listo.',
            'data'    => $event
        ]);
    }
}
