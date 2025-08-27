<?php

namespace Database\Seeders;

use App\Models\Productor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productores = [
            [
                'numero_productor' => '3',
                'nombre_completo' => 'ACOSTA, CARLOS ALBERTO',
                'cuit_cuil' => '23180458069',
                'kilos_entregados' => 45990,
                'superficie_medida' => 11.56,
                'cant_empleados_convenio' => 23,
                'total_salario_convenio' => 15130588.30,
                'promedio_salario_convenio' => 657851.6652,
                'cant_empleados_fuera_convenio' => 0,
                'total_salario_fuera_convenio' => 0,
                'promedio_salario_fuera_convenio' => 0,
                'jornal_promedio' => 30227.38,
                'ayc_sobre_jornal' => 14206.8686,
                'formula' => 0.0109,
                'total_ayc' => 7111376.501,
                'ts_determinada' => 615170.9776,
                'total_ayc_unitario' => 14206.8686,
                'total_jornales' => 500.559,
                'jornales_x_hectarea' => 43.301,
                'jornales_x_hectarea_sin_ayc' => 43.301,
                'jornales_x_hectarea_acumulados' => 84,
                'dif_jornales_x_has' => 40.699,
                'actividades' => '-111376',
            ],
            [
                'numero_productor' => '6',
                'nombre_completo' => 'AGRO SIANCAS S.R.L.',
                'cuit_cuil' => '33709351589',
                'kilos_entregados' => 10660,
                'superficie_medida' => 3.00,
                'cant_empleados_convenio' => 34,
                'total_salario_convenio' => 33747830.01,
                'promedio_salario_convenio' => 992583.2356,
                'cant_empleados_fuera_convenio' => 26,
                'total_salario_fuera_convenio' => 30552954.25,
                'promedio_salario_fuera_convenio' => 1175113.625,
                'jornal_promedio' => 30227.38,
                'ayc_sobre_jornal' => 14206.8686,
                'formula' => 0.1047,
                'total_ayc' => 15861480.1,
                'ts_determinada' => 5287160.035,
                'total_ayc_unitario' => 14206.8686,
                'total_jornales' => 1116.4656,
                'jornales_x_hectarea' => 372.1552,
                'jornales_x_hectarea_sin_ayc' => 372.1552,
                'jornales_x_hectarea_acumulados' => 84,
                'dif_jornales_x_has' => -288.1552,
                'actividades' => '-111317-111376',
            ],
            [
                'numero_productor' => '7',
                'nombre_completo' => 'ARAOZ, FERNANDO AVELINO',
                'cuit_cuil' => '20110800879',
                'kilos_entregados' => 2128,
                'superficie_medida' => 2.22,
                'cant_empleados_convenio' => 7,
                'total_salario_convenio' => 6504010,
                'promedio_salario_convenio' => 929144.2857,
                'cant_empleados_fuera_convenio' => 0,
                'total_salario_fuera_convenio' => 0,
                'promedio_salario_fuera_convenio' => 0,
                'jornal_promedio' => 30227.38,
                'ayc_sobre_jornal' => 14206.8686,
                'formula' => 0.1011,
                'total_ayc' => 3056884.7,
                'ts_determinada' => 1376975.09,
                'total_ayc_unitario' => 14206.8686,
                'total_jornales' => 215.1695,
                'jornales_x_hectarea' => 96.9232,
                'jornales_x_hectarea_sin_ayc' => 96.9232,
                'jornales_x_hectarea_acumulados' => 84,
                'dif_jornales_x_has' => -12.9232,
                'actividades' => '-111376',
            ],
        ];

        foreach ($productores as $productor) {
            Productor::firstOrCreate(
                ['numero_productor' => $productor['numero_productor']],
                $productor
            );
        }
    }
}
