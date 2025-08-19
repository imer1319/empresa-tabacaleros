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
        Schema::create('tipo_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del tipo de documento (ej. "Contrato de siembra")
            $table->text('descripcion')->nullable(); // Descripción del documento
            $table->boolean('es_obligatorio')->default(true); // Si es obligatorio para todos los productores
            $table->json('formatos_permitidos')->nullable(); // Formatos de archivo permitidos (PDF, JPG, etc.)
            $table->integer('tamaño_maximo')->nullable(); // Tamaño máximo en KB
            $table->text('instrucciones')->nullable(); // Instrucciones para el productor
            $table->boolean('activo')->default(true); // Si está activo o no
            $table->integer('orden')->default(0); // Orden de visualización
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documentos');
    }
};
