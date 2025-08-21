<?php

namespace App\Events;

use App\Models\Productor;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductorActualizado
{
    use Dispatchable, SerializesModels;

    public $productor;
    public $cambios;
    public $valoresAnteriores;
    public $usuario_id;

    /**
     * Create a new event instance.
     */
    public function __construct(Productor $productor, array $cambios, array $valoresAnteriores, ?int $usuario_id = null)
    {
        $this->productor = $productor;
        $this->cambios = $cambios;
        $this->valoresAnteriores = $valoresAnteriores;
        $this->usuario_id = $usuario_id;
    }
}