<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePublicationRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Publication;

class PublicationController extends Controller
{
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
}
