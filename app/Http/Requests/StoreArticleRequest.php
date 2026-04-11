<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Si la petición es PUT/PATCH (actualizar), hacemos que los campos sean opcionales
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');
        $rule = $isUpdate ? 'sometimes|required' : 'required';

        return [
            'title'       => "$rule|string|max:255",
            'description' => "$rule|string",
            'link'        => "$rule|url|max:500", // Valida que sea un link real (http/https)
            'date'        => "$rule|date",
            'category'    => "$rule|in:institutional,conference,interview,news", // Solo permite estos valores exactos
        ];
    }

    public function messages()
    {
        return [
            'category.in' => 'La categoría debe ser: institutional, conference, interview o news.',
            'link.url'    => 'El enlace proporcionado no es una URL válida.',
        ];
    }
}
