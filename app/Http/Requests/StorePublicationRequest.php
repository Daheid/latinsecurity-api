<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePublicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambia esto si necesitas lógica de autorización (ej. solo usuarios autenticados)
    }

    public function rules(): array
    {
        return [
            'title'                => 'required|string|max:255',
            'author'               => 'required|string|max:255',
            'journal_name'         => 'nullable|string|max:255',
            'volume_and_year'      => 'nullable|string|max:100',
            'isbn'                 => 'nullable|string|max:50',
            'legal_deposit_number' => 'nullable|string|max:100',
            'type_of_publication'  => 'required|string|max:100',
            'research_area'        => 'required|string|max:150',
            'abstract'             => 'required|string',
            'keywords'             => 'nullable|string', // Puedes cambiarlo a 'array' si los envías como arreglo
            'pdf_file'             => 'nullable|file|mimes:pdf|max:10240', // Opcional, solo PDF, máx 10MB
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
}
