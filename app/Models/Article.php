<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations; // 🚀 Importar trait de Spatie

class Article extends Model
{
    use HasFactory, HasTranslations; // 🚀 Usar el trait

    // 🚀 Definir explícitamente qué campos son traducibles
    public $translatable = [
        'title',
        'description'
    ];

    protected $fillable = [
        'title',
        'description',
        'link',
        'date',
        'category',
    ];
}
