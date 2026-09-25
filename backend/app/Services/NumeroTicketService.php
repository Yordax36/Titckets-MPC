<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class NumeroTicketService
{
    public function generate(): string
    {
        $year = date('Y');
        $prefijo = 'TKT-' . $year . '-';

        $ultimoTicket = Ticket::where('numero', 'LIKE', $prefijo . '%')
            ->orderByRaw("SUBSTRING(numero, " . (strlen($prefijo) + 1) . ") DESC")
            ->first();

        if ($ultimoTicket) {
            $ultimoNumero = (int) substr($ultimoTicket->numero, strlen($prefijo));
            $siguienteNumero = $ultimoNumero + 1;
        } else {
            $siguienteNumero = 1;
        }

        return $prefijo . str_pad($siguienteNumero, 6, '0', STR_PAD_LEFT);
    }
}
