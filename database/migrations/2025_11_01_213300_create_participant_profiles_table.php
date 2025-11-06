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
        Schema::create('participant_profiles', function (Blueprint $table) {
            $table->id();
            // Relación 1 a 1 con User
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name'); // Nombre de la empresa
            $table->string('nit')->unique();
            $table->string('phone');
            $table->string('sector');
            // Aquí se guardará la RUTA al archivo PDF/Imagen
            $table->string('portfolio_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_profiles');
    }
};
