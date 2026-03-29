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
        // Crear Roles
        $roles = ['Admin', 'Recepción', 'Técnico', 'Tesorero'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear usuario admin
        $admin = User::firstOrCreate([
            'email' => 'admin@laboratorio.com',
        ], [
            'name' => 'Administrador',
            'password' => bcrypt('password'),
        ]);

        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }
    }
}
