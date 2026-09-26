<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermisoSeeder::class,
            RolPermisoSeeder::class,
            AreaSeeder::class,
            CargoSeeder::class,
            AdminUserSeeder::class,
            AreaUsersSeeder::class,
            PersonalSeeder::class,
            TecnicoUsersSeeder::class,
            TecnicoMissingSeeder::class,
            TipoBienSeeder::class,
        ]);
    }
}
