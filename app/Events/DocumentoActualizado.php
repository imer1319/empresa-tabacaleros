<?php

namespace App\Events;

use App\Models\Documento;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentoActualizado
{
    use Dispatchable, SerializesModels;

    public $documento;
    public $cambios;
    public $valoresAnteriores;
    public $usuario_id;

    /**
     * Create a new event instance.
     */
    public function __construct(Documento $documento, array $cambios, array $valoresAnteriores, ?int $usuario_id = null)
    {
        $this->documento = $documento;
        $this->cambios = $cambios;
        $this->valoresAnteriores = $valoresAnteriores;
        $this->usuario_id = $usuario_id;
    }
}