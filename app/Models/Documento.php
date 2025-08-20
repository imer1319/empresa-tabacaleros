<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documento extends Model
{
    protected $fillable = [
        'productor_id',
        'tipo_documento_id',
        'nombre',
        'estado',
        'archivo_path',
        'archivo_nombre',
        'archivo_tamaño',
        'observaciones',
        'es_requerido',
        'fecha_entrega',
        'fecha_revision',
        'fecha_vencimiento',
        'revisado_por'
    ];

    protected $casts = [
        'fecha_entrega' => 'date',
        'fecha_revision' => 'date',
        'fecha_vencimiento' => 'date',
        'es_requerido' => 'boolean',
        'archivo_tamaño' => 'integer'
    ];

    /**
     * Relación con el modelo Productor
     */
    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class);
    }

    /**
     * Relación con el modelo TipoDocumento
     */
    public function tipoDocumento(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    /**
     * Scope para documentos requeridos
     */
    public function scopeRequeridos($query)
    {
        return $query->where('es_requerido', true);
    }

    /**
     * Scope para documentos por estado
     */
    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    /**
     * Scope para documentos vencidos
     */
    public function scopeVencidos($query)
    {
        return $query->where('fecha_vencimiento', '<', now()->toDateString())
            ->where('estado', '!=', 'aprobado');
    }

    /**
     * Scope para documentos próximos a vencer
     */
    public function scopeProximosAVencer($query, $dias = 30)
    {
        return $query->whereBetween('fecha_vencimiento', [
            now()->toDateString(),
            now()->addDays($dias)->toDateString()
        ])->where('estado', '!=', 'aprobado');
    }

    /**
     * Verificar si el documento está vencido
     */
    public function getEstaVencidoAttribute()
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }

        return $this->fecha_vencimiento < now()->toDateString() && $this->estado !== 'aprobado';
    }

    /**
     * Verificar si el documento está próximo a vencer
     */
    public function getProximoAVencerAttribute()
    {
        if (!$this->fecha_vencimiento) {
            return false;
        }

        $diasRestantes = now()->diffInDays($this->fecha_vencimiento, false);
        return $diasRestantes <= 30 && $diasRestantes >= 0 && $this->estado !== 'aprobado';
    }

    /**
     * Obtener días restantes hasta vencimiento
     */
    public function getDiasRestantesAttribute()
    {
        if (!$this->fecha_vencimiento) {
            return null;
        }

        return now()->diffInDays($this->fecha_vencimiento, false);
    }

    /**
     * Relación con el usuario revisor
     */
    public function revisor()
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getTamañoFormateadoAttribute()
    {
        if (!$this->archivo_tamaño) {
            return null;
        }

        $bytes = $this->archivo_tamaño;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
