<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    use HasFactory;

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
}