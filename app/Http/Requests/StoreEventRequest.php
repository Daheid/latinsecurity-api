<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'required|string|max:255',
            'subtitle'   => 'required|string|max:255',
            'location'   => 'required|string|max:255',
            'date'       => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i', // Formato de hora 24h ej. 14:30
            'end_time'   => 'nullable|date_format:H:i|after:start_time', // Debe ser después de la hora de inicio
            'language'   => 'nullable|string|max:255',
            'topics'     => 'nullable|string',
            'objectives' => 'nullable|string',
            'scopes'     => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // Máx 5MB
        ];
    }
}
