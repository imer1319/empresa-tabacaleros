<?php

namespace App\Models;

use App\Traits\RegistraHistorial;
use Illuminate\Database\Eloquent\Model;

class Productor extends Model
{
    use RegistraHistorial;
    protected $fillable = [
        'numero_productor',
        'nombre_completo',
        'cuit_cuil',
        'telefono',
        'email',
        'direccion',
        'localidad',
        'departamento',
        'estado_documentacion'
    ];

    protected $casts = [
        'estado_documentacion' => 'string'
    ];

    // Relación con documentos
    public function documentos()
    {
        return $this->hasMany(Documento::class)->orderBy('created_at', 'desc');
    }

    // Relación con historial
    public function historial()
    {
        return $this->hasMany(Historial::class, 'productor_id')->orderBy('created_at', 'desc');
    }

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class)
            ->orderBy('fecha_visita', 'desc')
            ->orderBy('id', 'asc');
    }
}
