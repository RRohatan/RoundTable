<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Registration extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Obtiene el usuario (participante) de esta inscripción.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene el evento de esta inscripción.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Obtiene las reuniones que ESTA inscripción ha solicitado.
     */
    public function requestedMeetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'requester_registration_id');
    }

    /**
     * Obtiene las reuniones que ESTA inscripción ha recibido.
     */
    public function receivedMeetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'receiver_registration_id');
    }
}
