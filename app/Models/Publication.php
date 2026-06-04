<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations; // 🚀 Importar trait

class Publication extends Model
{
    use HasFactory, HasTranslations; // 🚀 Usar trait

    // 🚀 Definir los campos que serán traducibles
    public $translatable = [
        'title',
        'type_of_publication',
        'research_area',
        'abstract',
        'keywords'
    ];

    protected $fillable = [
        'title',
        'author',
        'journal_name',
        'volume_and_year',
        'isbn',
        'legal_deposit_number',
        'type_of_publication',
        'research_area',
        'abstract',
        'keywords',
        'pdf_path',
        'downloads',
    ];
}
