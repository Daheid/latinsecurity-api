<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
{
    use HasFactory;

    // Definimos los campos que se pueden guardar de forma masiva
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
        'pdf_path', // Importante habilitar este campo
        'downloads',
    ];
}
