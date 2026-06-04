<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicationRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePublicationRequest;
use Stichoza\GoogleTranslate\GoogleTranslate; // 🚀 Importar traductor

class PublicationController extends Controller
{
    public function index(): JsonResponse
    {
        $publications = Publication::latest()->paginate(10);

        $publications->getCollection()->transform(function ($publication) {
            $publication->pdf_url = asset('storage/' . $publication->pdf_path);
            return $publication;
        });

        return response()->json([
            'success' => true,
            'message' => 'Publications retrieved successfully.',
            'data'    => $publications
        ], 200);
    }

    public function store(StorePublicationRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('publications', 'public');
            $validatedData['pdf_path'] = $pdfPath;
            unset($validatedData['pdf_file']);
        }

        // 🚀 Traducir datos antes de guardar
        $validatedData = $this->autoTranslateData($validatedData);

        $publication = Publication::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Publication created and translated successfully.',
            'data'    => $publication
        ], 201);
    }

    public function update(UpdatePublicationRequest $request, Publication $publication): JsonResponse
    {
        $validatedData = $request->validated();

        if ($request->hasFile('pdf_file')) {
            if (Storage::disk('public')->exists($publication->pdf_path)) {
                Storage::disk('public')->delete($publication->pdf_path);
            }

            $pdfPath = $request->file('pdf_file')->store('publications', 'public');
            $validatedData['pdf_path'] = $pdfPath;
            unset($validatedData['pdf_file']);
        }

        // 🚀 Traducir datos antes de actualizar
        $validatedData = $this->autoTranslateData($validatedData);

        $publication->update($validatedData);
        $publication->pdf_url = asset('storage/' . $publication->pdf_path);

        return response()->json([
            'success' => true,
            'message' => 'Publication updated and translated successfully.',
            'data'    => $publication
        ], 200);
    }

    public function destroy(Publication $publication): JsonResponse
    {
        if ($publication->pdf_path && Storage::disk('public')->exists($publication->pdf_path)) {
            Storage::disk('public')->delete($publication->pdf_path);
        }

        $publication->delete();

        return response()->json([
            'success' => true,
            'message' => 'Publication deleted successfully.'
        ], 200);
    }

    public function download(Publication $publication): JsonResponse
    {
        $publication->increment('downloads');

        return response()->json([
            'success'      => true,
            'message'      => 'Descarga registrada.',
            'downloads'    => $publication->downloads,
            'download_url' => asset('storage/' . $publication->pdf_path)
        ]);
    }

    /**
     * 🚀 Motor de Traducción Automática Dinámico
     */
    private function autoTranslateData(array $data): array
    {
        $tr = new GoogleTranslate();
        $tr->setSource(); // Detectar idioma automáticamente

        // Lista de campos que deben traducirse
        $fieldsToTranslate = ['title', 'type_of_publication', 'research_area', 'abstract', 'keywords'];

        foreach ($fieldsToTranslate as $field) {
            // Verificamos que el campo exista en el Request y no esté vacío
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
