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
                'es_obligatorio' => true,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 5120, // 5MB
                'instrucciones' => 'Subir el contrato firmado por ambas partes. Debe estar legible y completo.',
                'activo' => true,
                'orden' => 1
            ],
            [
                'nombre' => 'Cédula de Identidad',
                'descripcion' => 'Documento de identidad del productor',
                'es_obligatorio' => true,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 2048, // 2MB
                'instrucciones' => 'Subir ambos lados de la cédula de identidad. Debe estar vigente y legible.',
                'activo' => true,
                'orden' => 2
            ],
            [
                'nombre' => 'Constancia de CUIL/CUIT',
                'descripcion' => 'Constancia de inscripción en AFIP',
                'es_obligatorio' => true,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 2048, // 2MB
                'instrucciones' => 'Constancia actualizada de AFIP con el CUIL/CUIT del productor.',
                'activo' => true,
                'orden' => 3
            ],
            [
                'nombre' => 'Título de Propiedad',
                'descripcion' => 'Documento que acredita la propiedad del campo',
                'es_obligatorio' => true,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 10240, // 10MB
                'instrucciones' => 'Título de propiedad o contrato de arrendamiento del campo donde se realizará la siembra.',
                'activo' => true,
                'orden' => 4
            ],
            [
                'nombre' => 'Certificado Fitosanitario',
                'descripcion' => 'Certificado que acredita el estado sanitario del campo',
                'es_obligatorio' => true,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 5120, // 5MB
                'instrucciones' => 'Certificado emitido por SENASA u organismo competente.',
                'activo' => true,
                'orden' => 5
            ],
            [
                'nombre' => 'Póliza de Seguro',
                'descripcion' => 'Póliza de seguro agrícola del cultivo',
                'es_obligatorio' => false,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 5120, // 5MB
                'instrucciones' => 'Póliza de seguro que cubra el cultivo de tabaco (opcional pero recomendado).',
                'activo' => true,
                'orden' => 6
            ],
            [
                'nombre' => 'Análisis de Suelo',
                'descripcion' => 'Análisis químico y físico del suelo',
                'es_obligatorio' => false,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 3072, // 3MB
                'instrucciones' => 'Análisis de suelo realizado por laboratorio certificado (opcional).',
                'activo' => true,
                'orden' => 7
            ],
            [
                'nombre' => 'Constancia de Capacitación',
                'descripcion' => 'Certificado de capacitación en buenas prácticas agrícolas',
                'es_obligatorio' => false,
                'formatos_permitidos' => ['pdf', 'jpg', 'jpeg', 'png'],
                'tamaño_maximo' => 2048, // 2MB
                'instrucciones' => 'Certificado de participación en cursos de buenas prácticas agrícolas.',
                'activo' => true,
                'orden' => 8
            ],
            [
                'nombre' => 'intimacion anses',
                'descripcion' => 'Documento de intimación generado por ANSES',
                'es_obligatorio' => true,
                'formatos_permitidos' => ['pdf', 'docx', 'doc'],
                'tamaño_maximo' => 5120, // 5MB
                'instrucciones' => 'Documento de intimación de ANSES generado automáticamente durante la importación.',
                'activo' => true,
                'orden' => 9
            ]
        ];

        foreach ($tiposDocumento as $tipo) {
            TipoDocumento::create($tipo);
        }
    }
}
