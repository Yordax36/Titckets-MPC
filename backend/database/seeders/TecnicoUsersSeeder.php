<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TecnicoUsersSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $usuario = 'tecnico' . $i;
            $alias = 'Técnico ' . $i;

            $existingUser = DB::table('users')->where('email', "{$usuario}@tecnico.local")->first();

            if (!$existingUser) {
                DB::table('users')->insert([
                    'nombres' => $alias,
                    'apellidos' => '-',
                    'name' => $alias,
                    'email' => "{$usuario}@tecnico.local",
                    'password' => Hash::make('12345678'),
                    'rol_id' => 2,
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $userId = DB::getPdo()->lastInsertId();
                $this->command->info("Usuario creado: {$usuario}@tecnico.local (ID: {$userId})");
            } else {
                $userId = $existingUser->id;
                $this->command->info("Usuario ya existe: {$usuario}@tecnico.local (ID: {$userId})");
            }

            $tecnico = DB::table('tecnicos')->where('usuario', $usuario)->first();
            if ($tecnico && !$tecnico->user_id) {
                DB::table('tecnicos')->where('id', $tecnico->id)->update(['user_id' => $userId]);
                $this->command->info("Técnico {$alias} vinculado al usuario");
            }
        }

        $this->command->info('Listo!');
    }
}
