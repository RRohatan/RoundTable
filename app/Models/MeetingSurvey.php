<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class MeetingSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'user_id',
        'result',
    ];

    /**
     * Obtiene la reunión asociada a esta encuesta.
     */
    public function meeting(): BelongsTo
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Obtiene el participante que respondió esta encuesta.
     */
    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
