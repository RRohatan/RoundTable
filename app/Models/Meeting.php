<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Meeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'scheduled_start_time' => 'datetime',
        'scheduled_end_time' => 'datetime',
    ];

    /**
     * Obtiene el evento al que pertenece esta reunión.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Obtiene la inscripción (participante) que solicitó la reunión.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'requester_registration_id');
    }

    /**
     * Obtiene la inscripción (participante) que recibió la solicitud.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'receiver_registration_id');
    }

    /**
     * Obtiene las respuestas de la encuesta para esta reunión.
     */
    public function surveys(): HasMany
    {
        return $this->hasMany(MeetingSurvey::class);
    }
}
