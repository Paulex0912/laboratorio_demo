<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Roles
        $roles = ['Admin', 'Recepción', 'Técnico', 'Tesorero', 'Almacenero'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2. Crear usuario admin principal
        $admin = User::firstOrCreate([
            'email' => 'admin@laboratorio.com',
        ], [
            'name' => 'Administrador Joel Dent',
            'password' => bcrypt('password'), // Recuerda cambiarla luego
        ]);

        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        // 3. CARGAR HISTORIAL 2025 (Tu archivo Excel)
        $this->call([
            Trabajos2025Seeder::class,
        ]);
    }
}