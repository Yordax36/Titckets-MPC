<?php

namespace App\Services;

use App\Models\TicketHistorial;

class HistorialService
{
    public function registrar(
        int $ticketId,
        int $usuarioId,
        string $tipoCambio,
        ?string $valorAnterior = null,
        ?string $valorNuevo = null,
        ?string $comentario = null
    ): TicketHistorial {
        return TicketHistorial::create([
            'ticket_id' => $ticketId,
            'usuario_id' => $usuarioId,
            'tipo_cambio' => $tipoCambio,
            'valor_anterior' => $valorAnterior,
            'valor_nuevo' => $valorNuevo,
            'comentario' => $comentario,
        ]);
    }
}
