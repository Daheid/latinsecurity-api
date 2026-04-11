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
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i', // Formato de hora 24h ej. 14:30
            'end_time'   => 'required|date_format:H:i|after:start_time', // Debe ser después de la hora de inicio
            'topics'     => 'required|string',
            'objectives' => 'nullable|string',
            'scopes'     => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // Máx 5MB
        ];
    }
}
