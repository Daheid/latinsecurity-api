<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'document_id',
    ];

    // Una asistencia pertenece a un evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
