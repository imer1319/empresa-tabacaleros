<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productor_id')->constrained()->onDelete('cascade');
            $table->text('descripcion');
            $table->date('fecha_visita');
            $table->date('fecha_proxima_cita')->nullable();
            $table->enum('estado', ['pendiente', 'asistio', 'no_asistio'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};