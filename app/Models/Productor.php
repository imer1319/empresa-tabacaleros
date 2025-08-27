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
        'kilos_entregados',
        'superficie_medida',
        'cant_empleados_convenio',
        'total_salario_convenio',
        'promedio_salario_convenio',
        'cant_empleados_fuera_convenio',
        'total_salario_fuera_convenio',
        'promedio_salario_fuera_convenio',
        'jornal_promedio',
        'ayc_sobre_jornal',
        'formula',
        'total_ayc',
        'ts_determinada',
        'total_ayc_unitario',
        'total_jornales',
        'jornales_x_hectarea',
        'jornales_x_hectarea_sin_ayc',
        'jornales_x_hectarea_acumulados',
        'dif_jornales_x_has',
        'actividades'
    ];

    protected $casts = [
        'kilos_entregados' => 'integer',
        'superficie_medida' => 'decimal:2',
        'cant_empleados_convenio' => 'integer',
        'total_salario_convenio' => 'decimal:2',
        'promedio_salario_convenio' => 'decimal:2',
        'cant_empleados_fuera_convenio' => 'integer',
        'total_salario_fuera_convenio' => 'decimal:2',
        'promedio_salario_fuera_convenio' => 'decimal:2',
        'jornal_promedio' => 'decimal:2',
        'ayc_sobre_jornal' => 'decimal:4',
        'formula' => 'decimal:4',
        'total_ayc' => 'decimal:2',
        'ts_determinada' => 'decimal:2',
        'total_ayc_unitario' => 'decimal:2',
        'total_jornales' => 'decimal:2',
        'jornales_x_hectarea' => 'decimal:4',
        'jornales_x_hectarea_sin_ayc' => 'decimal:4',
        'jornales_x_hectarea_acumulados' => 'decimal:4',
        'dif_jornales_x_has' => 'decimal:4'
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
