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
            $table->string('numero_productor')->unique(); // Nro de Grupo Económico
            $table->string('nombre_completo');            // Productor Cabeza de Grupo
            $table->string('cuit_cuil')->unique();

            // ---- Info de contacto ----
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->string('localidad')->nullable();
            $table->string('departamento')->nullable();
            $table->enum('estado_documentacion', ['En proceso', 'Aprobado', 'Faltante'])->default('En proceso');

            // ---- Datos productivos ----
            $table->bigInteger('kilos_entregados')->nullable();
            $table->decimal('superficie_medida', 10, 2)->nullable();

            // Empleados convenio
            $table->integer('cant_empleados_convenio')->nullable();
            $table->decimal('total_salario_convenio', 15, 2)->nullable();
            $table->decimal('promedio_salario_convenio', 15, 4)->nullable();

            // Empleados fuera de convenio
            $table->integer('cant_empleados_fuera_convenio')->nullable();
            $table->decimal('total_salario_fuera_convenio', 15, 2)->nullable();
            $table->decimal('promedio_salario_fuera_convenio', 15, 2)->nullable();

            // Jornales y cálculos
            $table->decimal('jornal_promedio', 15, 2)->nullable();
            $table->decimal('ayc_sobre_jornal', 15, 4)->nullable();
            $table->decimal('formula', 15, 4)->nullable();
            $table->decimal('total_ayc', 15, 2)->nullable();
            $table->decimal('ts_determinada', 15, 2)->nullable();
            $table->decimal('total_ayc_unitario', 15, 2)->nullable();

            $table->decimal('total_jornales', 15, 2)->nullable();
            $table->decimal('jornales_x_hectarea', 15, 4)->nullable();
            $table->decimal('jornales_x_hectarea_sin_ayc', 15, 4)->nullable();
            $table->decimal('jornales_x_hectarea_acumulados', 15, 4)->nullable();
            $table->decimal('dif_jornales_x_has', 15, 4)->nullable();

            // Observaciones
            $table->string('actividades')->nullable();

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
