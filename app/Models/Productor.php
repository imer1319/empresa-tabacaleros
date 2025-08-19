<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productor extends Model
{
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
        return $this->hasMany(Documento::class);
    }

    // // Relación con comunicaciones
    // public function comunicaciones()
    // {
    //     return $this->hasMany(Comunicacion::class);
    // }
}
