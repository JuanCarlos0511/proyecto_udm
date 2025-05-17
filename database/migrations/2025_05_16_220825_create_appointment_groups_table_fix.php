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
        // 1. Crear la tabla appointment_groups
        Schema::create('appointment_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        
        // 2. Añadir la restricción de clave foránea a la tabla appointments
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('appointment_group_id')->references('id')->on('appointment_groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Eliminar la restricción de clave foránea
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['appointment_group_id']);
        });
        
        // 2. Eliminar la tabla appointment_groups
        Schema::dropIfExists('appointment_groups');
    }
};
