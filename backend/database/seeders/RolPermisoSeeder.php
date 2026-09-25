<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolPermisoSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $permisos = DB::table('permisos')->pluck('id', 'nombre');

        $rolPermisos = [];

        // Administrador: TODOS los permisos
        foreach ($permisos as $nombre => $id) {
            $rolPermisos[] = [
                'rol_id' => 1,
                'permiso_id' => $id,
            ];
        }

        // Tecnico: permisos limitados
        $tecnicoPermisos = [
            'ver_tickets_asignados',
            'cambiar_estado',
            'agregar_respuesta',
            'ver_historial',
        ];
        foreach ($tecnicoPermisos as $nombre) {
            if (isset($permisos[$nombre])) {
                $rolPermisos[] = [
                    'rol_id' => 2,
                    'permiso_id' => $permisos[$nombre],
                ];
            }
        }

        // Area Usuaria: permisos basicos
        $areaUsuarioPermisos = [
            'ver_mis_tickets',
            'crear_ticket',
            'agregar_respuesta',
            'ver_historial',
        ];
        foreach ($areaUsuarioPermisos as $nombre) {
            if (isset($permisos[$nombre])) {
                $rolPermisos[] = [
                    'rol_id' => 3,
                    'permiso_id' => $permisos[$nombre],
                ];
            }
        }

        DB::table('rol_permisos')->insert($rolPermisos);
    }
}
