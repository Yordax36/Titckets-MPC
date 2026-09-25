<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TicketService
{
    protected NumeroTicketService $numeroService;
    protected HistorialService $historialService;

    public function __construct(NumeroTicketService $numeroService, HistorialService $historialService)
    {
        $this->numeroService = $numeroService;
        $this->historialService = $historialService;
    }

    public function create(array $data, User $user): Ticket
    {
        return DB::transaction(function () use ($data, $user) {
            $ticket = Ticket::create([
                'numero' => $this->numeroService->generate(),
                'titulo' => $data['titulo'],
                'descripcion' => $data['descripcion'],
                'categoria' => $data['categoria'],
                'prioridad' => $data['prioridad'],
                'estado' => 'abierto',
                'area_id' => $user->area_id,
                'creado_por' => $user->id,
            ]);

            $this->historialService->registrar(
                $ticket->id,
                $user->id,
                'creacion',
                null,
                $ticket->estado,
                'Ticket creado'
            );

            return $ticket->load(['area', 'createdBy', 'assignedTo']);
        });
    }

    public function update(Ticket $ticket, array $data, User $user): Ticket
    {
        return DB::transaction(function () use ($ticket, $data, $user) {
            $campos = ['titulo', 'descripcion', 'categoria', 'prioridad'];

            foreach ($campos as $campo) {
                if (isset($data[$campo]) && $data[$campo] !== $ticket->{$campo}) {
                    $this->historialService->registrar(
                        $ticket->id,
                        $user->id,
                        $campo,
                        $ticket->{$campo},
                        $data[$campo]
                    );
                }
            }

            $ticket->update($data);

            return $ticket->load(['area', 'createdBy', 'assignedTo']);
        });
    }

    public function cambiarEstado(Ticket $ticket, string $nuevoEstado, User $user, ?string $comentario = null): Ticket
    {
        return DB::transaction(function () use ($ticket, $nuevoEstado, $user, $comentario) {
            $estadoAnterior = $ticket->estado;
            $ticket->update(['estado' => $nuevoEstado]);

            $this->historialService->registrar(
                $ticket->id,
                $user->id,
                'estado',
                $estadoAnterior,
                $nuevoEstado,
                $comentario
            );

            return $ticket->fresh(['area', 'createdBy', 'assignedTo']);
        });
    }

    public function asignar(Ticket $ticket, int $tecnicoId, User $user): Ticket
    {
        return DB::transaction(function () use ($ticket, $tecnicoId, $user) {
            $tecnico = \App\Models\Tecnico::find($tecnicoId);
            if (!$tecnico || !$tecnico->user_id) {
                throw new \InvalidArgumentException('El tecnico seleccionado no tiene un usuario asociado');
            }

            $userId = $tecnico->user_id;
            $asignadoAnterior = $ticket->asignado_a;
            $ticket->update(['asignado_a' => $userId]);

            $this->historialService->registrar(
                $ticket->id,
                $user->id,
                'asignacion',
                $asignadoAnterior ? (string) $asignadoAnterior : null,
                (string) $userId
            );

            return $ticket->fresh(['area', 'createdBy', 'assignedTo']);
        });
    }

    public function getByUser(User $user)
    {
        $query = Ticket::with(['area', 'createdBy', 'assignedTo']);

        if ($user->rol->nombre === 'Tecnico') {
            $query->where('asignado_a', $user->id);
        } elseif ($user->rol->nombre === 'Area Usuaria') {
            $query->where('creado_por', $user->id);
        }

        return $query->latest()->get();
    }

    public function getHistorial(Ticket $ticket)
    {
        return $ticket->historial()->with('usuario')->latest()->get();
    }
}
