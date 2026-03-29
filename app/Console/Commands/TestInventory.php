<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\InventoryMovement;
use App\Models\WorkOrder;
use App\Models\WorkOrderMaterial;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Events\StockBelowMinimum;

class TestInventory extends Command
{
    protected $signature = 'test:inventory';
    protected $description = 'Prueba el flujo de inventario y consumo en ordenes de trabajo';

    public function handle()
    {
        $this->info("Iniciando prueba de inventario...");

        $product = Product::create([
            'code' => 'TEST-' . rand(1000, 9999),
            'name' => 'Resina de Prueba',
            'unit_measure' => 'gramo',
            'stock_current' => 10,
            'stock_min' => 3,
            'stock_max' => 50,
            'cost_price' => 20
        ]);

        InventoryMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => 10,
            'unit_cost' => 20,
            'user_id' => 1,
            'date' => now()
        ]);

        $user = User::first() ?? User::factory()->create();
        $patient = Patient::first() ?? Patient::create([
            'name' => 'Paciente de Prueba',
            'phone' => '123456789',
            'email' => 'paciente@test.com'
        ]);

        $order = WorkOrder::first();

        if (!$order) {
            $order = WorkOrder::create([
                'patient_id' => $patient->id,
                'technician_id' => $user->id,
                'status' => 'en_proceso',
                'type' => 'Corona',
                'material' => 'Zirconio',
                'due_date' => now()->addDays(2),
            ]);
        }

        $this->info("--- Antes del consumo ---");
        $this->info("Stock: " . $product->stock_current);

        DB::transaction(function () use ($order, $product) {
            WorkOrderMaterial::create([
                'work_order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 8
            ]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'type' => 'out',
                'quantity' => 8,
                'unit_cost' => $product->cost_price,
                'reference_type' => WorkOrder::class ,
                'reference_id' => $order->id,
                'user_id' => 1,
                'date' => now()
            ]);

            $product->stock_current -= 8;
            $product->save();

            if ($product->stock_current < $product->stock_min) {
                event(new StockBelowMinimum($product));
                $this->warn("Evento StockBelowMinimum disparado!");
            }
        });

        $product->refresh();
        $this->info("--- Despues del consumo ---");
        $this->info("Stock: " . $product->stock_current);
        $this->info("Movimientos Kardex: " . $product->movements()->count());
    }
}
