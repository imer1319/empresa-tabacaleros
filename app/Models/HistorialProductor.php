<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialProductor extends Model
{
    protected $table = 'historial_productor';

    protected $fillable = [
        'productor_id',
        'tipo_cambio',
        'descripcion',
        'detalles',
        'usuario_id'
    ];

    protected $casts = [
        'detalles' => 'json'
    ];

    /**
     * Relación con el modelo Productor
     */
    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class);
    }

    /**
     * Relación con el modelo User
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
