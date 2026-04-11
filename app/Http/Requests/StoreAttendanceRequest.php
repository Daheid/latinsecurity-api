<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Obtenemos el evento de la URL para la regla de unicidad
        $eventId = $this->route('event')->id;

        return [
            'name'        => 'required|string|max:255',
            'document_id' => 'nullable|string|max:50',
            'email'       => [
                'required',
                'email',
                // Esta regla asegura que el email sea único, pero SOLO dentro de este evento específico
                Rule::unique('attendances')->where(function ($query) use ($eventId) {
                    return $query->where('event_id', $eventId);
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Este correo ya ha sido registrado para este evento.',
        ];
    }
}
