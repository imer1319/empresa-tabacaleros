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
        Schema::create('historial_cambios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('productor_id')->constrained('productors')->onDelete('cascade');
            $table->string('modelo_type');
            $table->unsignedBigInteger('modelo_id');
            $table->enum('tipo_operacion', ['creacion', 'actualizacion', 'eliminacion', 'restauracion']);
            $table->json('antes')->nullable();
            $table->json('despues')->nullable();
            $table->json('cambios')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['modelo_type', 'modelo_id'], 'historial_modelo_index');
            $table->index('user_id', 'historial_user_index');
            $table->index('productor_id', 'historial_productor_index');
            $table->index('created_at', 'historial_created_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_cambios');
    }
};
