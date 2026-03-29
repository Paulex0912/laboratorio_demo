<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Pago de Ordenes', 'type' => 'ingreso'],
            ['name' => 'Aporte Socios', 'type' => 'ingreso'],
            ['name' => 'Compra Insumos', 'type' => 'egreso'],
            ['name' => 'Servicios Básicos', 'type' => 'egreso'],
            ['name' => 'Planilla/Pagos Técnicos', 'type' => 'egreso'],
            ['name' => 'Otros', 'type' => 'egreso'],
        ];

        foreach ($categories as $cat) {
            \App\Models\CashCategory::firstOrCreate($cat);
        }
    }
}
