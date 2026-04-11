<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Obtener los artículos (Permite filtrar por categoría)
     */
    public function index(Request $request): JsonResponse
    {
        // Iniciamos la consulta
        $query = Article::query();

        // Si el frontend envía ?category=news, filtramos los resultados
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Ordenamos por fecha descendente (los más nuevos primero) y paginamos
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
        $article = Article::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Artículo creado exitosamente.',
            'data'    => $article
        ], 201);
    }

    /**
     * Actualizar un artículo existente
     */
    public function update(StoreArticleRequest $request, Article $article): JsonResponse
    {
        $article->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Artículo actualizado exitosamente.',
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
}
