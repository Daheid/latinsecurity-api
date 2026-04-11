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
            // Al menos uno de los dos debería enviarse al cerrar el evento
            'result'         => 'required_without:implementation|string',
            'implementation' => 'required_without:result|string',
        ];
    }

    public function messages()
    {
        return [
            'result.required_without' => 'Debes proporcionar un resultado o una implementación para cerrar el evento.',
        ];
    }
}
