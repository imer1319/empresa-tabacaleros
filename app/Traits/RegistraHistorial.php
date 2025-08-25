<?php

namespace App\Traits;

use App\Models\Historial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

trait RegistraHistorial
{
    protected static function bootRegistraHistorial()
    {
        // No registrar historial durante los seeders
        if (app()->runningInConsole() && app()->environment() === 'local') {
            return;
        }

        static::created(function (Model $modelo) {
            Log::info('Registrando creación:', ['modelo' => get_class($modelo), 'id' => $modelo->id]);
            Historial::registrarCambio(
                $modelo,
                'creacion',
                null,
                $modelo->getAttributes(),
                $modelo->getAttributes()
            );
        });

        static::updated(function (Model $modelo) {
            Log::info('Verificando actualización:', ['modelo' => get_class($modelo), 'id' => $modelo->id]);
            $cambios = $modelo->getChanges();
            $original = $modelo->getOriginal();
            $actual = $modelo->getAttributes();

            // Solo registrar si hubo cambios significativos
            if (!empty($cambios)) {
                // Filtrar solo los campos que realmente cambiaron
                $cambiosSignificativos = array_intersect_key($cambios, $original);
                $valoresAnteriores = array_intersect_key($original, $cambiosSignificativos);
                $valoresNuevos = array_intersect_key($actual, $cambiosSignificativos);

                // Preparar los cambios en el formato esperado
                $cambiosFormateados = [];
                foreach ($cambiosSignificativos as $campo => $valor) {
                    $cambiosFormateados[$campo] = [
                        'anterior' => $valoresAnteriores[$campo] ?? null,
                        'nuevo' => $valoresNuevos[$campo] ?? null
                    ];
                }

                Log::info('Registrando actualización:', [
                    'cambios' => $cambiosFormateados,
                    'valores_anteriores' => $valoresAnteriores,
                    'valores_nuevos' => $valoresNuevos
                ]);

                Historial::registrarCambio(
                    $modelo,
                    'actualizacion',
                    $valoresAnteriores,
                    $valoresNuevos,
                    $cambiosFormateados
                );
            }
        });

        static::deleted(function (Model $modelo) {
            Log::info('Registrando eliminación:', ['modelo' => get_class($modelo), 'id' => $modelo->id]);
            Historial::registrarCambio(
                $modelo,
                'eliminacion',
                $modelo->getAttributes(),
                null,
                null
            );
        });

        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $modelo) {
                Historial::registrarCambio(
                    $modelo,
                    'restauracion',
                    null,
                    $modelo->getAttributes(),
                    $modelo->getAttributes()
                );
            });
        }
    }
}
