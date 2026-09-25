<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $permisos = [
            // Modulo: Usuarios
            ['nombre' => 'crear_usuario',    'modulo' => 'usuarios', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'editar_usuario',   'modulo' => 'usuarios', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'eliminar_usuario', 'modulo' => 'usuarios', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'ver_usuarios',     'modulo' => 'usuarios', 'created_at' => $now, 'updated_at' => $now],

            // Modulo: Areas
            ['nombre' => 'crear_area',    'modulo' => 'areas', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'editar_area',   'modulo' => 'areas', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'eliminar_area', 'modulo' => 'areas', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'ver_areas',     'modulo' => 'areas', 'created_at' => $now, 'updated_at' => $now],

            // Modulo: Tickets
            ['nombre' => 'ver_todos_los_tickets', 'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'ver_tickets_asignados', 'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'ver_mis_tickets',       'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'crear_ticket',          'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'cambiar_estado',        'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'cambiar_prioridad',     'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'asignar_tecnico',       'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],
            ['nombre' => 'reasignar_ticket',      'modulo' => 'tickets', 'created_at' => $now, 'updated_at' => $now],

            // Modulo: Historial
            ['nombre' => 'ver_historial', 'modulo' => 'historial', 'created_at' => $now, 'updated_at' => $now],

            // Modulo: Respuestas
            ['nombre' => 'agregar_respuesta', 'modulo' => 'respuestas', 'created_at' => $now, 'updated_at' => $now],

            // Modulo: Dashboard
            ['nombre' => 'ver_estadisticas', 'modulo' => 'dashboard', 'created_at' => $now, 'updated_at' => $now],

            // Modulo: Auditoria
            ['nombre' => 'ver_auditoria', 'modulo' => 'auditoria', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('permisos')->insert($permisos);
    }
}
