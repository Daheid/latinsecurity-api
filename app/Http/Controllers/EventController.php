<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\CompleteEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendEventCertificatesJob;

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
        if ($event->status === 'ready') {
            return response()->json(['success' => false, 'message' => 'Evento ya finalizado.'], 400);
        }

        $data = $request->validated();

        // Manejar la subida del certificado (imagen de fondo o plantilla)
        if ($request->hasFile('certificate_file')) {
            // Si ya existía uno, lo borramos
            if ($event->certificate_path) {
                Storage::disk('public')->delete($event->certificate_path);
            }

            $path = $request->file('certificate_file')->store('certificates', 'public');
            $data['certificate_path'] = $path;
        }

        $data['status'] = 'ready';

        $event->update($data);

        // Añadimos la URL completa para el frontend
        $event->certificate_url = $event->certificate_path ? asset('storage/' . $event->certificate_path) : null;

        // 🚀 Despachamos el trabajo en segundo plano para generar y enviar los PDFs
        SendEventCertificatesJob::dispatch($event);

        return response()->json([
            'success' => true,
            'message' => 'Evento actualizado y marcado como listo. Los certificados se están generando y enviando en segundo plano.',
            'data'    => $event
        ]);
    }
}
