<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            
            // Relaciones con usuarios y propiedades
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Usuario que hizo la acción
            $table->unsignedBigInteger('propiedad_id')->nullable(); // ID de la propiedad afectada
            
            // Datos de la accion 'crear', 'editar', 'eliminar'
            $table->string('accion'); 
            
            // Datos de los cambios 
            $table->json('valores_anteriores')->nullable(); // Solo para 'editar'
            $table->json('valores_nuevos')->nullable(); // Para 'crear' y 'editar'
            
            // Campos de fecha automaticos (created_at servira como fecha/hora del evento)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
