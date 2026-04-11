<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'location',
        'date',
        'start_time',
        'end_time',
        'topics',
        'objectives',
        'scopes',
        'status',
        'result',
        'implementation',
        'youtube_link',      // Nuevo
        'certificate_path',   // Nuevo
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
