<?php
$user = App\Models\User::where('email', 'admin@dental.com')->first();
if ($user) {
    echo "Usuario encontrado: " . $user->name . "\n";
    $roleAdmin = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin']);
    $roleRecepcion = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Recepción']);
    $roleTesorero = Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Tesorero']);

    $user->assignRole($roleAdmin);
    $user->assignRole($roleRecepcion);
    $user->assignRole($roleTesorero);
    echo "Roles asignados: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
}
else {
    echo "Usuario no encontrado.\n";
}
