<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Area;
use App\Models\Supplier;
use App\Models\PurchaseOrder;

class TestModules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prueba e inserta registros en HR y Purchases para validar funcionamiento y vistas.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando Test QA de Módulos (Compras y RRHH)...');

        // 1. Crear Area y Empleado
        $area = Area::create(['name' => 'Laboratorio Clínico', 'description' => 'Área de análisis']);
        $employee = Employee::create([
            'name' => 'Juan Pérez Testing',
            'dni' => '12345678',
            'position' => 'Técnico Avanzado',
            'area_id' => $area->id,
            'start_date' => now()
        ]);
        $this->info("✔ Empleado Creado: {$employee->name} en el área de {$area->name}");

        // 2. Crear Proveedor
        $supplier = Supplier::create([
            'business_name' => 'Dental Supplies Co. SAC',
            'ruc' => '20123456789',
            'contact_name' => 'Pedro Proveedor',
            'payment_term_days' => 15
        ]);
        $this->info("✔ Proveedor Creado: {$supplier->business_name}");

        // 3. Crear Orden de Compra temporal
        $user = \App\Models\User::first();
        if ($user) {
            $po = PurchaseOrder::create([
                'supplier_id' => $supplier->id,
                'status' => 'borrador',
                'created_by' => $user->id,
                'expected_date' => now()->addDays(5)
            ]);
            $this->info("✔ Orden de Compra Creada: #OC-000{$po->id}");
        }
        else {
            $this->error('Falta usuario base para la orden');
        }

        $this->info('Test Finalizado Correctamente.');
    }
}
