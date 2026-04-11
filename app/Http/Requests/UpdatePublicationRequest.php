<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePublicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'                => 'sometimes|required|string|max:255',
            'author'               => 'sometimes|required|string|max:255',
            'journal_name'         => 'nullable|string|max:255',
            'volume_and_year'      => 'nullable|string|max:100',
            'isbn'                 => 'nullable|string|max:50',
            'legal_deposit_number' => 'nullable|string|max:100',
            'type_of_publication'  => 'sometimes|required|string|max:100',
            'research_area'        => 'sometimes|required|string|max:150',
            'abstract'             => 'sometimes|required|string',
            'keywords'             => 'nullable|string',
            // El PDF ahora es 'nullable' (opcional) en la actualización
            'pdf_file'             => 'nullable|file|mimes:pdf|max:10240',
        ];
    }
}
