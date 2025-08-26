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
            'estado_documentacion' => $this->faker->randomElement(['En proceso', 'Aprobado', 'Faltante']),
            
            // Datos productivos
            'kilos_entregados' => $this->faker->numberBetween(1000, 100000),
            'superficie_medida' => $this->faker->randomFloat(2, 1, 1000),
            
            // Empleados convenio
            'cant_empleados_convenio' => $this->faker->numberBetween(1, 50),
            'total_salario_convenio' => $this->faker->randomFloat(2, 10000, 1000000),
            'promedio_salario_convenio' => $this->faker->randomFloat(2, 50000, 200000),
            
            // Empleados fuera de convenio
            'cant_empleados_fuera_convenio' => $this->faker->numberBetween(0, 10),
            'total_salario_fuera_convenio' => $this->faker->randomFloat(2, 0, 500000),
            'promedio_salario_fuera_convenio' => $this->faker->randomFloat(2, 0, 100000),
            
            // Jornales y cálculos
            'jornal_promedio' => $this->faker->randomFloat(2, 1000, 5000),
            'ayc_sobre_jornal' => $this->faker->randomFloat(4, 0.1, 0.5),
            'formula' => $this->faker->randomFloat(4, 0.5, 2),
            'total_ayc' => $this->faker->randomFloat(2, 5000, 50000),
            'ts_determinada' => $this->faker->randomFloat(2, 10000, 100000),
            'total_ayc_unitario' => $this->faker->randomFloat(2, 100, 1000),
            'total_jornales' => $this->faker->randomFloat(2, 1000, 10000),
            'jornales_x_hectarea' => $this->faker->randomFloat(4, 0.5, 5),
            'jornales_x_hectarea_sin_ayc' => $this->faker->randomFloat(4, 0.3, 4),
            'jornales_x_hectarea_acumulados' => $this->faker->randomFloat(4, 1, 10),
            'dif_jornales_x_has' => $this->faker->randomFloat(4, -2, 2),
            
            // Observaciones
            'actividades' => $this->faker->sentence()
        ];
    }
}