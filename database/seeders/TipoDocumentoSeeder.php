<?php

namespace Database\Seeders;

use App\Models\TipoDocumento;
use Illuminate\Database\Seeder;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposDocumento = [
            [
                'nombre' => 'Contrato de Siembra',
                'descripcion' => 'Contrato firmado entre el productor y la empresa tabacalera',
                'activo' => true,
                'orden' => 1
            ],
            [
                'nombre' => 'Cédula de Identidad',
                'descripcion' => 'Documento de identidad del productor',
                'activo' => true,
                'orden' => 2
            ],
            [
                'nombre' => 'Constancia de CUIL/CUIT',
                'descripcion' => 'Constancia de inscripción en AFIP',
                'activo' => true,
                'orden' => 3
            ],
            [
                'nombre' => 'Título de Propiedad',
                'descripcion' => 'Documento que acredita la propiedad del campo',
                'activo' => true,
                'orden' => 4
            ],
            [
                'nombre' => 'Certificado Fitosanitario',
                'descripcion' => 'Certificado que acredita el estado sanitario del campo',
                'activo' => true,
                'orden' => 5
            ],
            [
                'nombre' => 'Póliza de Seguro',
                'descripcion' => 'Póliza de seguro agrícola del cultivo',
                'activo' => true,
                'orden' => 6
            ],
            [
                'nombre' => 'Análisis de Suelo',
                'descripcion' => 'Análisis químico y físico del suelo',
                'activo' => true,
                'orden' => 7
            ],
            [
                'nombre' => 'Constancia de Capacitación',
                'descripcion' => 'Certificado de capacitación en buenas prácticas agrícolas',
                'activo' => true,
                'orden' => 8
            ],
            [
                'nombre' => 'Intimación ANSES',
                'descripcion' => 'Documento de intimación generado por ANSES',
                'activo' => true,
                'orden' => 9
            ]
        ];

        foreach ($tiposDocumento as $tipo) {
            TipoDocumento::create($tipo);
        }
    }
}
