<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\AccountReceivable;
use \Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener un paciente o crear uno si no hay
        $patient = Patient::first() ?? Patient::factory()->create();

        // Crear una factura vencida hace 5 dias
        $invoice1 = Invoice::create([
            'patient_id' => $patient->id,
            'invoice_type' => 'Factura',
            'series_number' => 'F001-99990001',
            'issue_date' => Carbon::now()->subDays(35),
            'due_date' => Carbon::now()->subDays(5),
            'discount_percentage' => 0,
            'subtotal' => 1000,
            'igv' => 180,
            'total' => 1180,
            'status' => 'pendiente',
            'issued_by' => 1,
        ]);
        
        AccountReceivable::create([
            'patient_id' => $patient->id,
            'invoice_id' => $invoice1->id,
            'total_amount' => 1180,
            'paid_amount' => 0,
            'balance' => 1180,
            'due_date' => Carbon::now()->subDays(5),
            'status' => 'pendiente',
        ]);

        // Crear una factura que vence en 2 dias (pronto)
        $invoice2 = Invoice::create([
            'patient_id' => $patient->id,
            'invoice_type' => 'Boleta',
            'series_number' => 'B001-99990002',
            'issue_date' => Carbon::now()->subDays(28),
            'due_date' => Carbon::now()->addDays(2),
            'discount_percentage' => 0,
            'subtotal' => 500,
            'igv' => 90,
            'total' => 590,
            'status' => 'parcial',
            'issued_by' => 1,
        ]);

        AccountReceivable::create([
            'patient_id' => $patient->id,
            'invoice_id' => $invoice2->id,
            'total_amount' => 590,
            'paid_amount' => 100,
            'balance' => 490,
            'due_date' => Carbon::now()->addDays(2),
            'status' => 'parcial',
        ]);

        // Crear una factura que vence en 15 dias (sana)
        $invoice3 = Invoice::create([
            'patient_id' => $patient->id,
            'invoice_type' => 'Recibo',
            'series_number' => 'R001-99990003',
            'issue_date' => Carbon::now()->subDays(15),
            'due_date' => Carbon::now()->addDays(15),
            'discount_percentage' => 10,
            'subtotal' => 300,
            'igv' => 54, // simulación
            'total' => 318.60,
            'status' => 'pendiente',
            'issued_by' => 1,
        ]);

        AccountReceivable::create([
            'patient_id' => $patient->id,
            'invoice_id' => $invoice3->id,
            'total_amount' => 318.60,
            'paid_amount' => 0,
            'balance' => 318.60,
            'due_date' => Carbon::now()->addDays(15),
            'status' => 'pendiente',
        ]);
    }
}
