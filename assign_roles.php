<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

$roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
$roleRRHH = Role::firstOrCreate(['name' => 'RRHH']);
$roleLogistica = Role::firstOrCreate(['name' => 'Logística']);

$user = User::first();
if ($user) {
    if (!$user->hasRole('Admin')) {
        $user->assignRole('Admin');
    }
    if (!$user->hasRole('RRHH')) {
        $user->assignRole('RRHH');
    }
    if (!$user->hasRole('Logística')) {
        $user->assignRole('Logística');
    }
    echo "Roles assigned to {$user->email}\n";
}
else {
    echo "No user found\n";
}
