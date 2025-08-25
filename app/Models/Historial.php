<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Historial extends Model
{
    protected $table = 'historial_cambios';

    protected $fillable = [
        'user_id',
        'productor_id',
        'modelo_type',
        'modelo_id',
        'tipo_operacion',
        'antes',
        'despues',
        'cambios',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'antes' => 'array',
        'despues' => 'array',
        'cambios' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Obtiene el usuario que realizó el cambio
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtiene el productor relacionado con el cambio
     */
    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class, 'productor_id');
    }

    /**
     * Obtiene el modelo que fue modificado
     */
    public function modelo(): MorphTo
    {
        return $this->morphTo('modelo');
    }

    /**
     * Registra un cambio en el historial
     */
    public static function registrarCambio(
        Model $modelo,
        string $tipoOperacion,
        ?array $antes = null,
        ?array $despues = null,
        ?array $cambios = null
    ): self {
        Log::info('Iniciando registro de cambio:', [
            'modelo' => get_class($modelo),
            'id' => $modelo->getKey(),
            'tipo_operacion' => $tipoOperacion
        ]);

        // Formatear los cambios para asegurar la estructura correcta
        $cambiosFormateados = [];
        if ($cambios) {
            foreach ($cambios as $campo => $valor) {
                $cambiosFormateados[$campo] = [
                    'anterior' => $antes[$campo] ?? null,
                    'nuevo' => $despues[$campo] ?? null
                ];
            }
        }

        $request = request();

        $data = [
            'user_id' => Auth::id(),
            'modelo_type' => get_class($modelo),
            'modelo_id' => $modelo->getKey(),
            'tipo_operacion' => $tipoOperacion,
            'antes' => $antes,
            'despues' => $despues,
            'cambios' => $cambiosFormateados,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ];

        // Si el modelo tiene productor_id o es un Productor, registramos el ID del productor
        if (isset($modelo->productor_id)) {
            $data['productor_id'] = $modelo->productor_id;
        } elseif ($modelo instanceof Productor) {
            $data['productor_id'] = $modelo->getKey();
        }

        Log::info('Guardando registro de cambio:', ['data' => $data]);
        $historial = self::create($data);
        Log::info('Registro de cambio guardado:', ['historial_id' => $historial->id]);
        return $historial;
    }
}
