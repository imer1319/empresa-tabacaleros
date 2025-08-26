<?php

namespace Database\Factories;

use App\Models\Historial;
use App\Models\Productor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HistorialFactory extends Factory
{
    protected $model = Historial::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'productor_id' => Productor::factory(),
            'modelo_type' => Productor::class,
            'modelo_id' => function (array $attributes) {
                return $attributes['productor_id'];
            },
            'tipo_operacion' => $this->faker->randomElement(['creacion', 'actualizacion', 'eliminacion']),
            'antes' => null,
            'despues' => null,
            'cambios' => null,
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear()
        ];
    }
}