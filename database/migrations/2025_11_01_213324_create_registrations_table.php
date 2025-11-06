<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // El participante
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // Rol en el EVENTO: 'supplier' (oferente) o 'buyer' (demandante)
            $table->string('role');
            $table->text('role_description'); // "Qué ofrezco" o "Qué demando"
            $table->text('preferred_availability')->nullable();

            // Regla: Un usuario solo puede inscribirse una vez por evento
            $table->unique(['user_id', 'event_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
