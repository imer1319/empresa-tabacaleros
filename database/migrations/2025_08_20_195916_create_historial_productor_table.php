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
        Schema::create('historial_productor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productor_id')->constrained('productors')->onDelete('cascade');
            $table->string('tipo_cambio'); // documento, datos, estado
            $table->string('descripcion');
            $table->json('detalles')->nullable(); // Almacena información adicional del cambio
            $table->foreignId('usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['productor_id', 'tipo_cambio']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_productor');
    }
};
