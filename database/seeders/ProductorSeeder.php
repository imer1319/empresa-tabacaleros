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
                'numero_productor' => '40',
                'nombre_completo' => 'MAZZONE, JULIO CESAR',
                'cuit_cuil' => '20140459063',
                'telefono' => null,
                'email' => null,
                'direccion' => 'FCA SANTA ROSA',
                'localidad' => 'EL BORDO',
                'departamento' => 'La Viña',
                'estado_documentacion' => 'En proceso'
            ],
            [
                'numero_productor' => '70',
                'nombre_completo' => 'RUPNIK SAMARDZICH, HECTOR FRANCISCO',
                'cuit_cuil' => '20122029027',
                'telefono' => null,
                'email' => null,
                'direccion' => 'FCA LOS LOS - CHICOANA',
                'localidad' => 'CHICOANA',
                'departamento' => 'Chicoana',
                'estado_documentacion' => 'En proceso'
            ],
            [
                'numero_productor' => '130',
                'nombre_completo' => 'PEREZ, RAMON ENRIQUE',
                'cuit_cuil' => '20133473980',
                'telefono' => null,
                'email' => null,
                'direccion' => 'FCA EL RODEO',
                'localidad' => 'CERRILLOS',
                'departamento' => 'Cerrillos',
                'estado_documentacion' => 'En proceso'
            ]
        ];

        foreach ($productores as $productor) {
            Productor::firstOrCreate(
                ['numero_productor' => $productor['numero_productor']],
                $productor
            );
        }
    }
}