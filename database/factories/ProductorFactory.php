<?php

namespace Database\Factories;

use App\Models\Productor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductorFactory extends Factory
{
    protected $model = Productor::class;

    public function definition()
    {
        return [
            'numero_productor' => 'FET' . $this->faker->unique()->numberBetween(1000, 9999),
            'nombre_completo' => $this->faker->name(),
            'cuit_cuil' => $this->faker->numerify('##########'),
            'telefono' => $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'direccion' => $this->faker->streetAddress(),
            'localidad' => $this->faker->city(),
            'departamento' => $this->faker->state(),
            'estado_documentacion' => $this->faker->randomElement(['En proceso', 'Aprobado', 'Faltante'])
        ];
    }
}