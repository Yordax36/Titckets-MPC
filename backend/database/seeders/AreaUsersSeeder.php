<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Area;

class AreaUsersSeeder extends Seeder
{
    public function run(): void
    {
        $rolArea = \App\Models\Role::where('nombre', 'Area Usuaria')->first();

        if (!$rolArea) {
            $this->command->error('No se encontró el rol "Area Usuaria". Ejecuta RoleSeeder primero.');
            return;
        }

        $areas = Area::all();
        $countUsers = 0;

        foreach ($areas as $area) {
            $email = $area->correo;
            $password = $area->password_correo ?: 'MPC@2026!';

            if (!$email) continue;

            $user = User::where('email', $email)->first();

            if (!$user) {
                User::create([
                    'nombres' => $area->nombre,
                    'apellidos' => '',
                    'name' => $area->nombre,
                    'email' => $email,
                    'username' => $email,
                    'password' => Hash::make($password),
                    'dni' => str_pad($area->id, 8, '0', STR_PAD_LEFT),
                    'cargo' => 'Área Institucional',
                    'rol_id' => $rolArea->id,
                    'estado' => 'activo',
                ]);
                $countUsers++;
            }
        }

        $this->command->info("Se crearon {$countUsers} usuarios institucionales.");
    }
}
