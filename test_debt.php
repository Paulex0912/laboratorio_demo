<?php
$patient = App\Models\Patient::first();
if (!$patient) {
    echo "Creando paciente Demo...\n";
    $patient = App\Models\Patient::create(['name' => 'Demo VIP', 'dni' => '12345678', 'created_by' => 1]);
}
// Limpiamos deudas huerfanas de pruebas anteriores
App\Models\AccountReceivable::where('patient_id', $patient->id)->delete();

$debt1 = App\Models\AccountReceivable::create([
    'patient_id' => $patient->id,
    'total_amount' => 1500,
    'balance' => 1500,
    'due_date' => now()->addDays(5),
    'status' => 'pendiente'
]);

$debt2 = App\Models\AccountReceivable::create([
    'patient_id' => $patient->id,
    'total_amount' => 500,
    'balance' => 500,
    'due_date' => now()->subDays(5),
    'status' => 'pendiente'
]);

echo "Deuda generada para {$patient->name}. Total Balance de las 2 cuentas: " . ($debt1->balance + $debt2->balance) . "\n";
