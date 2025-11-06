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
        Schema::create('organizer_profiles', function (Blueprint $table) {
            $table->id();
            // Relación 1 a 1 con User
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('logo_url')->nullable();
            // El 'nombre_entidad' se puede tomar de la tabla 'users' (campo 'name')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizer_profiles');
    }
};
