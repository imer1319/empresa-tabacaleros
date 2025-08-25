<?php

namespace App\Models;

use App\Traits\RegistraHistorial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Cita extends Model
{
    use HasFactory, RegistraHistorial;

    protected $fillable = [
        'productor_id',
        'descripcion',
        'fecha_visita',
        'fecha_proxima_cita',
        'estado',
    ];

    protected $casts = [
        'fecha_visita' => 'date',
        'fecha_proxima_cita' => 'date',
    ];

    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class);
    }

    public function historial(): MorphMany
    {
        return $this->morphMany(Historial::class, 'modelo', 'modelo_type', 'modelo_id')->orderBy('created_at', 'desc');
    }
}