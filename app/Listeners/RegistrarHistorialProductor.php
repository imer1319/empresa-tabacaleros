<?php

namespace App\Listeners;

use App\Events\ProductorActualizado;
use App\Models\HistorialProductor;

class RegistrarHistorialProductor
{
    /**
     * Handle the event.
     */
    public function handle(ProductorActualizado $event): void
    {
        $cambiosSignificativos = array_intersect_key(
            $event->cambios,
            array_flip([
                'nombre_completo',
                'telefono',
                'email',
                'direccion',
                'localidad',
                'departamento',
                'estado_documentacion'
            ])
        );

        if (empty($cambiosSignificativos)) {
            return;
        }

        if (empty($event->cambios) && !empty($event->valoresAnteriores)) {
            $descripcion = 'Se eliminó el productor con los siguientes datos:';
        } else {
            $descripcion = empty($event->valoresAnteriores) ? 'Se creó el productor con los siguientes datos:' : 'Se actualizaron los siguientes datos:';
        }
        $detalles = [];

        foreach ($cambiosSignificativos as $campo => $valorNuevo) {
            $valorAnterior = $event->valoresAnteriores[$campo] ?? null;
            $detalles[$campo] = [
                'anterior' => $valorAnterior ?: 'No especificado',
                'nuevo' => $valorNuevo ?: 'No especificado'
            ];
        }

        HistorialProductor::create([
            'productor_id' => $event->productor->id,
            'tipo_cambio' => 'datos',
            'descripcion' => $descripcion,
            'detalles' => $detalles,
            'usuario_id' => $event->usuario_id
        ]);
    }
}
