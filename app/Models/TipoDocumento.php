<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'es_obligatorio',
        'formatos_permitidos',
        'tamaño_maximo',
        'instrucciones',
        'activo',
        'orden'
    ];

    protected $casts = [
        'es_obligatorio' => 'boolean',
        'formatos_permitidos' => 'array',
        'activo' => 'boolean',
        'orden' => 'integer',
        'tamaño_maximo' => 'integer'
    ];

    /**
     * Relación con documentos
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(Documento::class, 'tipo', 'nombre');
    }

    /**
     * Scope para tipos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para tipos obligatorios
     */
    public function scopeObligatorios($query)
    {
        return $query->where('es_obligatorio', true);
    }

    /**
     * Scope ordenados
     */
    public function scopeOrdenados($query)
    {
        return $query->orderBy('orden');
    }

    /**
     * Obtener formatos permitidos como string
     */
    public function getFormatosPermitidosStringAttribute()
    {
        if (!$this->formatos_permitidos) {
            return 'Todos los formatos';
        }
        
        return implode(', ', $this->formatos_permitidos);
    }

    /**
     * Obtener tamaño máximo formateado
     */
    public function getTamañoMaximoFormateadoAttribute()
    {
        if (!$this->tamaño_maximo) {
            return 'Sin límite';
        }

        if ($this->tamaño_maximo < 1024) {
            return $this->tamaño_maximo . ' KB';
        }

        return round($this->tamaño_maximo / 1024, 2) . ' MB';
    }
}
