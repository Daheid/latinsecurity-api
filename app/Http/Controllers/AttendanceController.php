<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class AttendanceController extends Controller
{
    /**
     * Obtener la lista de asistentes de un evento específico
     */
    public function index(Event $event): JsonResponse
    {
        // Obtenemos los asistentes paginados
        $attendees = $event->attendances()->latest()->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Lista de asistencia recuperada.',
            'data'    => $attendees
        ]);
    }

    /**
     * Registrar una nueva asistencia a un evento
     */
    public function store(StoreAttendanceRequest $request, Event $event): JsonResponse
    {
        // Lógica de negocio: No permitir registro si el evento ya está "ready" (cerrado)
        if ($event->status === 'ready') {
            return response()->json([
                'success' => false,
                'message' => 'No puedes registrar asistencia a un evento que ya ha concluido.'
            ], 400); // 400 Bad Request
        }

        // Creamos la asistencia vinculada automáticamente al ID de este evento
        $attendance = $event->attendances()->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Asistencia registrada con éxito.',
            'data'    => $attendance
        ], 201);
    }
}
