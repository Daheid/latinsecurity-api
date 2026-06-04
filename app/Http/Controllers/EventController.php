<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\CompleteEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendEventCertificatesJob;
use Stichoza\GoogleTranslate\GoogleTranslate; // 🚀 Importar traductor

class EventController extends Controller
{
    public function index(): JsonResponse
    {
        $status = request('status');

        $events = Event::when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->latest('date')->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $events
        ]);
    }

    public function store(StoreEventRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // 🚀 Traducimos título, subtítulo, locación, temas, etc. antes de crear
        $validatedData = $this->autoTranslateData($validatedData);

        $event = Event::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Evento creado y traducido exitosamente.',
            'data'    => $event
        ], 201);
    }

    public function markAsReady(CompleteEventRequest $request, Event $event): JsonResponse
    {
        if ($event->status === 'ready') {
            return response()->json(['success' => false, 'message' => 'Evento ya finalizado.'], 400);
        }

        $data = $request->validated();

        if ($request->hasFile('certificate_file')) {
            if ($event->certificate_path) {
                Storage::disk('public')->delete($event->certificate_path);
            }

            $path = $request->file('certificate_file')->store('certificates', 'public');
            $data['certificate_path'] = $path;
        }

        $data['status'] = 'ready';

        // 🚀 Traducimos los campos nuevos que llegan al finalizar (result, implementation)
        $data = $this->autoTranslateData($data);

        $event->update($data);

        $event->certificate_url = $event->certificate_path ? asset('storage/' . $event->certificate_path) : null;

        SendEventCertificatesJob::dispatch($event);

        return response()->json([
            'success' => true,
            'message' => 'Evento actualizado, traducido y marcado como listo.',
            'data'    => $event
        ]);
    }

    /**
     * 🚀 Motor de Traducción Automática Dinámico
     */
    private function autoTranslateData(array $data): array
    {
        $tr = new GoogleTranslate();
        $tr->setSource(); // Detectar idioma automáticamente

        // Lista de todos los campos que componen un evento
        $fieldsToTranslate = [
            'title',
            'subtitle',
            'location',
            'topics',
            'objectives',
            'scopes',
            'result',
            'implementation'
        ];

        foreach ($fieldsToTranslate as $field) {
            if (!empty($data[$field])) {
                $data[$field] = [
                    'es' => $tr->setTarget('es')->translate($data[$field]),
                    'en' => $tr->setTarget('en')->translate($data[$field]),
                ];
            }
        }

        return $data;
    }
}
