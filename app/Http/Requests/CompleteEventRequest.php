<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'result'           => 'required_without:implementation|string',
            'implementation'    => 'required_without:result|string',
            'youtube_link'     => 'nullable|url',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'result.required_without' => 'Debes proporcionar un resultado o una implementación para cerrar el evento.',
        ];
    }
}
