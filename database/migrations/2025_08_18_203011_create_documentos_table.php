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
            $table->string('nombre');
            $table->enum('estado', ['pendiente', 'entregado', 'aprobado', 'rechazado', 'vencido'])->default('pendiente');
            $table->string('archivo_path')->nullable();
            $table->string('archivo_nombre')->nullable();
            $table->integer('archivo_tamaño')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('es_requerido')->default(true);
            $table->date('fecha_entrega')->nullable();
            $table->date('fecha_revision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->unsignedBigInteger('revisado_por')->nullable();
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
