<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate; // 🚀 Importar motor de traducción

class ArticleController extends Controller
{
    /**
     * Obtener los artículos (Permite filtrar por categoría)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Article::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->orderBy('date', 'desc')->paginate(12);

        return response()->json([
            'success' => true,
            'data'    => $articles
        ]);
    }

    /**
     * Crear un artículo nuevo
     */
    public function store(StoreArticleRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // 🚀 Traducimos el título y la descripción antes de guardarlos
        $validatedData = $this->autoTranslateData($validatedData);

        $article = Article::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Artículo creado y traducido exitosamente.',
            'data'    => $article
        ], 201);
    }

    /**
     * Actualizar un artículo existente
     */
    public function update(StoreArticleRequest $request, Article $article): JsonResponse
    {
        $validatedData = $request->validated();

        // 🚀 Traducimos el título y la descripción antes de actualizarlos
        $validatedData = $this->autoTranslateData($validatedData);

        $article->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Artículo actualizado y traducido exitosamente.',
            'data'    => $article
        ]);
    }

    /**
     * Eliminar un artículo
     */
    public function destroy(Article $article): JsonResponse
    {
        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Artículo eliminado exitosamente.'
        ]);
    }

    /**
     * 🚀 Motor de Traducción Automática Dinámico
     */
    private function autoTranslateData(array $data): array
    {
        $tr = new GoogleTranslate();
        $tr->setSource(); // Detectar idioma automáticamente

        $fieldsToTranslate = ['title', 'description'];

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
