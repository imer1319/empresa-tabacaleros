<?php

namespace Database\Factories;

use App\Models\Documento;
use App\Models\Productor;
use App\Models\TipoDocumento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentoFactory extends Factory
{
    protected $model = Documento::class;

    public function definition()
    {
        return [
            'productor_id' => Productor::factory(),
            'tipo_documento_id' => TipoDocumento::factory(),
            'nombre' => $this->faker->word() . '.pdf',
            'estado' => $this->faker->randomElement(['pendiente', 'entregado', 'aprobado', 'rechazado', 'vencido']),
            'archivo_path' => 'documentos/' . $this->faker->uuid() . '.pdf',
            'archivo_nombre' => $this->faker->word() . '.pdf',
            'archivo_tamaño' => $this->faker->numberBetween(1000, 5000000),
            'observaciones' => $this->faker->optional()->sentence(),
            'es_requerido' => $this->faker->boolean(),
            'fecha_entrega' => $this->faker->optional()->date(),
            'fecha_revision' => $this->faker->optional()->date(),
            'fecha_vencimiento' => $this->faker->optional()->date(),
            'revisado_por' => User::factory(),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear()
        ];
    }
}