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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productor_id')->constrained('productors')->onDelete('cascade');
            $table->foreignId('tipo_documento_id')->constrained('tipo_documentos')->onDelete('cascade');
            $table->string('nombre'); // Nombre del documento (ej. "Contrato de siembra")
            $table->enum('estado', ['pendiente', 'entregado', 'aprobado', 'rechazado', 'vencido'])->default('pendiente');
            $table->string('archivo_path')->nullable(); // Ruta del archivo subido
            $table->string('archivo_nombre')->nullable(); // Nombre original del archivo
            $table->integer('archivo_tamaño')->nullable(); // Tamaño del archivo en bytes
            $table->text('observaciones')->nullable(); // Observaciones o comentarios
            $table->boolean('es_requerido')->default(true); // Si es un documento obligatorio
            $table->date('fecha_entrega')->nullable(); // Fecha de entrega del documento
            $table->date('fecha_revision')->nullable(); // Fecha de revisión
            $table->date('fecha_vencimiento')->nullable(); // Fecha de vencimiento del documento
            $table->unsignedBigInteger('revisado_por')->nullable(); // ID del usuario que revisó
            $table->timestamps();

            $table->index(['productor_id', 'tipo_documento_id']);
            $table->index(['estado']);
            $table->index(['fecha_vencimiento']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
