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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->foreignId('user_id')->constrained('users');
            $table->string('subject');
            $table->enum('status', ['Solicitado','Agendado', 'Completado', 'Cancelado'])->default('Agendado');
            $table->enum('modality', ['Consultorio', 'Domicilio'])->default('Consultorio');
            $table->unsignedBigInteger('appointment_group_id')->nullable(); // Sin restricción de clave foránea por ahora
            $table->decimal('price', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};