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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            // A qué deuda está abonando
            $table->foreignId('account_receivable_id')->constrained()->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();

            // Transaccionalidad
            $table->decimal('amount', 10, 2); // Monto pagado
            $table->string('payment_method'); // Efectivo, Tarjeta, Transferencia, Cheque
            $table->string('reference_number')->nullable(); // Ej: Nro de Operación / Cheque

            $table->date('payment_date');
            $table->foreignId('received_by')->constrained('users')->cascadeOnDelete(); // Cajero
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_payments');
    }
};
