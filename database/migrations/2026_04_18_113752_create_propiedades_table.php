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
        Schema::create('propiedades', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_titulo'); // Nombre descriptivo
            $table->string('tipo');          // Casa, departamento, local, etc.
            $table->text('direccion');       // Direccion completa
            $table->decimal('precio', 15, 2); // Valor de la propiedad)
            $table->text('descripcion');     // Descripcion detallada
            $table->string('estado');        // Disponible, reservada, vendida
            $table->text('imagenes_path')->nullable(); 
            $table->decimal('superficie_m2', 8, 2); // Superficie en metros cuadrados
            $table->integer('ambientes');          // Cantidad de ambientes

            // crea una relación. propiedad pertenece a un usuario (operador o admin)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('propiedades');
    }
};
