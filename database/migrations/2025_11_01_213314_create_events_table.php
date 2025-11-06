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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            // El usuario 'organizador' que creó el evento
            $table->foreignId('user_id')->constrained('users');
            $table->string('name'); // Nombre del evento
            $table->date('date');
            $table->string('location');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('meeting_duration_minutes');
            $table->string('registration_link')->unique()->nullable();
            $table->integer('supplier_limit'); // Límite de 'oferentes'
            $table->dateTime('registration_deadline'); // Cierre de inscripciones
            $table->string('status')->default('RegistrationOpen'); // Ej: 'SchedulingActive', 'InProgress', 'Finished'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
