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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            // Quién solicita (Referencia a la inscripción, no al usuario)
            $table->foreignId('requester_registration_id')->constrained('registrations');

            // Quién recibe (Referencia a la inscripción, no al usuario)
            $table->foreignId('receiver_registration_id')->constrained('registrations');

            // 'pending', 'confirmed', 'rejected', 'in_progress', 'completed', 'cancelled'
            $table->string('status');

            $table->dateTime('scheduled_start_time')->nullable();
            $table->dateTime('scheduled_end_time')->nullable();
            $table->string('assigned_table')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
