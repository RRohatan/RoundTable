<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Event extends Model
{
    use HasFactory;

    /**
     * Usamos $guarded para permitir asignación masiva de todos los campos
     * ya que el formulario de creación de eventos tendrá muchos campos.
     */
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i', // Trata la hora como un objeto de tiempo
        'end_time' => 'datetime:H:i',
        'registration_deadline' => 'datetime',
    ];

    /**
     * Obtiene el usuario (organizador) que creó este evento.
     */
    public function organizer(): BelongsTo
    {
        // Renombramos la relación para claridad
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene todas las inscripciones para este evento.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Obtiene todas las reuniones agendadas en este evento.
     */
    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }
}
