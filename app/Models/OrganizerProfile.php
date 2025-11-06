<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrganizerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'logo_url',
    ];

    /**
     * Obtiene el usuario (organizador) al que pertenece este perfil.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
