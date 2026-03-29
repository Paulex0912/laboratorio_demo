<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account_receivables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->unique()->constrained()->cascadeOnDelete();

            // Saldos
            $table->decimal('total_amount', 10, 2); // Monto original adeudado
            $table->decimal('paid_amount', 10, 2)->default(0); // Cuánto se ha pagado hasta ahora
            $table->decimal('balance', 10, 2); // Cuánto falta por pagar (total - paid)

            // Estado y Tiempos
            $table->date('due_date')->nullable(); // Fecha de Vencimiento de la Deuda
            $table->string('status')->default('pendiente'); // pendiente, parcial, pagado

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_receivables');
    }
};
