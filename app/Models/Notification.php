<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Notification extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Los atributos que deben ser casteados a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Obtiene el usuario al que pertenece esta notificación.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
