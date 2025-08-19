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
        Schema::create('productors', function (Blueprint $table) {
            $table->id();
            $table->string('numero_productor')->unique();
            $table->string('nombre_completo');
            $table->string('cuit_cuil')->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion');
            $table->string('localidad');
            $table->string('departamento');
            $table->enum('estado_documentacion', ['En proceso', 'Aprobado', 'Faltante'])->default('En proceso');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productors');
    }
};
