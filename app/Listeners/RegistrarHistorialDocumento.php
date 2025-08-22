<?php

namespace App\Listeners;

use App\Events\DocumentoActualizado;
use App\Models\HistorialProductor;

class RegistrarHistorialDocumento
{
    /**
     * Handle the event.
     */
    public function handle(DocumentoActualizado $event): void
    {
        $campos = ['estado', 'fecha_entrega', 'fecha_vencimiento'];
        $cambiosSignificativos = array_intersect_key(
            $event->cambios,
            array_flip($campos)
        );

        // Determinar el tipo de operación
        if (empty($event->valoresAnteriores)) {
            $descripcion = 'Se creó el documento: ' . $event->documento->nombre . ' (Estado: ' . $event->documento->estado . ')';
            $cambiosSignificativos = array_intersect_key(
                $event->cambios,
                array_flip($campos)
            );
        } elseif (empty($event->cambios)) {
            $descripcion = 'Se eliminó el documento: ' . $event->documento->nombre . ' (Estado anterior: ' . ($event->valoresAnteriores['estado'] ?? 'No especificado') . ')';
            $cambiosSignificativos = array_intersect_key(
                $event->valoresAnteriores,
                array_flip($campos)
            );
        } else {
            $descripcion = 'Se actualizó el documento: ' . $event->documento->nombre . ' (Estado: ' . $event->documento->estado . ')';
        }

        $detalles = [
            'tipo_documento' => $event->documento->tipoDocumento->nombre,
            'cambios' => []
        ];

        foreach ($cambiosSignificativos as $campo => $valor) {
            $valorAnterior = empty($event->valoresAnteriores) ? null : ($event->valoresAnteriores[$campo] ?? null);
            $valorNuevo = empty($event->cambios) ? null : ($event->cambios[$campo] ?? $valorAnterior);
            
            if ($campo === 'estado') {
                $detalles['cambios'][$campo] = [
                    'anterior' => $valorAnterior ?? 'No especificado',
                    'nuevo' => $valorNuevo ?? 'No especificado'
                ];
            } elseif (in_array($campo, ['fecha_entrega', 'fecha_vencimiento'])) {
                $detalles['cambios'][$campo] = [
                    'anterior' => $valorAnterior ? date('Y-m-d', strtotime($valorAnterior)) : 'No especificado',
                    'nuevo' => $valorNuevo ? date('Y-m-d', strtotime($valorNuevo)) : 'No especificado'
                ];
            }
        }

        HistorialProductor::create([
            'productor_id' => $event->documento->productor_id,
            'tipo_cambio' => 'documento',
            'descripcion' => $descripcion,
            'detalles' => $detalles,
            'usuario_id' => $event->usuario_id
        ]);
    }
}