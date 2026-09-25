<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->rol->nombre === 'Administrador') {
            return true;
        }

        if ($user->rol->nombre === 'Tecnico' && $ticket->asignado_a === $user->id) {
            return true;
        }

        if ($user->rol->nombre === 'Area Usuaria' && $ticket->creado_por === $user->id) {
            return true;
        }

        return false;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->rol->nombre === 'Administrador') {
            return true;
        }

        if ($user->rol->nombre === 'Tecnico' && $ticket->asignado_a === $user->id) {
            return true;
        }

        if ($user->rol->nombre === 'Area Usuaria' && $ticket->creado_por === $user->id) {
            return in_array($ticket->estado, ['asignado', 'en_proceso']);
        }

        return false;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->rol->nombre === 'Administrador';
    }

    public function asignar(User $user): bool
    {
        return $user->rol->nombre === 'Administrador';
    }

    public function cambiarEstado(User $user, Ticket $ticket): bool
    {
        if ($user->rol->nombre === 'Administrador') {
            return true;
        }

        if ($user->rol->nombre === 'Tecnico' && $ticket->asignado_a === $user->id) {
            return true;
        }

        return false;
    }
}
