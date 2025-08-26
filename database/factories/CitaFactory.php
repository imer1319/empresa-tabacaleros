<?php

namespace Database\Factories;

use App\Models\Cita;
use App\Models\Productor;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitaFactory extends Factory
{
    protected $model = Cita::class;

    public function definition()
    {
        return [
            'productor_id' => Productor::factory(),
            'fecha_visita' => $this->faker->date(),
            'fecha_proxima_cita' => $this->faker->date(),
            'descripcion' => $this->faker->paragraph(),
            'estado' => $this->faker->randomElement(['pendiente', 'asistio', 'no_asistio']),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => $this->faker->dateTimeThisYear()
        ];
    }
}