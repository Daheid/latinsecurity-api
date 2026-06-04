<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations; // 🚀 Importar trait

class Event extends Model
{
    use HasFactory, HasTranslations; // 🚀 Usar trait

    // 🚀 Definir los campos que serán traducibles
    public $translatable = [
        'title',
        'subtitle',
        'location',
        'topics',
        'objectives',
        'scopes',
        'result',
        'implementation'
    ];

    protected $fillable = [
        'title',
        'subtitle',
        'location',
        'date',
        'start_time',
        'end_time',
        'language',
        'topics',
        'objectives',
        'scopes',
        'status',
        'result',
        'implementation',
        'youtube_link',
        'certificate_path',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
