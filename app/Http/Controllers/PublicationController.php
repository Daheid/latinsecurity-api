<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicationRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdatePublicationRequest;

class PublicationController extends Controller
{
    public function index(): JsonResponse
    {
        // 1. Obtenemos las publicaciones ordenadas por las más recientes
        // Paginate(10) devolverá 10 resultados por página y los metadatos de paginación
        $publications = Publication::latest()->paginate(10);

        // 2. Transformamos los datos para añadir la URL completa del PDF al vuelo
        $publications->getCollection()->transform(function ($publication) {
            // Creamos un nuevo atributo dinámico llamado 'pdf_url'
            // asset('storage/...') genera la URL base completa (ej. http://127.0.0.1:8000/storage/publications/archivo.pdf)
            $publication->pdf_url = asset('storage/' . $publication->pdf_path);

            return $publication;
        });

        // 3. Retornamos la respuesta
        return response()->json([
            'success' => true,
            'message' => 'Publications retrieved successfully.',
            'data'    => $publications
        ], 200);
    }
    public function store(StorePublicationRequest $request): JsonResponse
    {
        // 1. Obtener los datos ya validados
        $validatedData = $request->validated();

        // 2. Manejar la subida del archivo PDF
        if ($request->hasFile('pdf_file')) {
            // Guarda el archivo en storage/app/public/publications
            $pdfPath = $request->file('pdf_file')->store('publications', 'public');

            // Añadimos la ruta del archivo al array de datos validados
            $validatedData['pdf_path'] = $pdfPath;

            // Opcional: eliminar el objeto file del array si vas a hacer una inserción masiva
            unset($validatedData['pdf_file']);
        }

        // 3. Guardar en la base de datos (Ejemplo usando Eloquent)
        $publication = Publication::create($validatedData);

        // 4. Retornar una respuesta JSON de éxito
        return response()->json([
            'success' => true,
            'message' => 'Publication created successfully.',
            'data'    => $validatedData // Retorna $publication si usaste el modelo
        ], 201);
    }

    public function update(UpdatePublicationRequest $request, Publication $publication): JsonResponse
    {
        $validatedData = $request->validated();

        // 1. Verificar si el usuario está subiendo un NUEVO archivo PDF
        if ($request->hasFile('pdf_file')) {

            // Borramos el PDF viejo del disco 'public' para no acumular basura
            if (Storage::disk('public')->exists($publication->pdf_path)) {
                Storage::disk('public')->delete($publication->pdf_path);
            }

            // Guardamos el nuevo archivo
            $pdfPath = $request->file('pdf_file')->store('publications', 'public');
            $validatedData['pdf_path'] = $pdfPath;

            unset($validatedData['pdf_file']);
        }

        // 2. Actualizamos el registro en la base de datos
        $publication->update($validatedData);

        // 3. Añadimos la URL del PDF a la respuesta por comodidad del frontend
        $publication->pdf_url = asset('storage/' . $publication->pdf_path);

        return response()->json([
            'success' => true,
            'message' => 'Publication updated successfully.',
            'data'    => $publication
        ], 200);
    }

    /**
     * Eliminar una publicación y su archivo PDF
     */
    public function destroy(Publication $publication): JsonResponse
    {
        // 1. Verificar si el PDF existe físicamente en el disco y eliminarlo
        if ($publication->pdf_path && Storage::disk('public')->exists($publication->pdf_path)) {
            Storage::disk('public')->delete($publication->pdf_path);
        }

        // 2. Eliminar el registro de la base de datos
        $publication->delete();

        // 3. Retornar una respuesta de éxito
        return response()->json([
            'success' => true,
            'message' => 'Publication deleted successfully.'
        ], 200); // 200 OK
    }
    /**
     * Registrar descarga y devolver URL del PDF
     */
    public function download(Publication $publication): JsonResponse
    {
        // 1. Incrementamos el contador en 1 mágicamente con Laravel
        $publication->increment('downloads');

        // 2. Retornamos la respuesta con la cantidad actualizada y la URL
        return response()->json([
            'success'      => true,
            'message'      => 'Descarga registrada.',
            'downloads'    => $publication->downloads,
            'download_url' => asset('storage/' . $publication->pdf_path)
        ]);
    }
}
