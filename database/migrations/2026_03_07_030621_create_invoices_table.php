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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('series', 4); // F001, B001
            $table->string('number', 8); // 00000001
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained()->nullOnDelete();

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('igv', 10, 2)->default(0); // 18% del subtotal
            $table->decimal('total', 10, 2)->default(0);

            $table->enum('status', ['pendiente', 'parcial', 'pagada', 'anulada'])->default('pendiente');

            $table->date('date_issued');
            $table->date('due_date'); // Fecha de Vencimiento
            $table->string('cancellation_reason')->nullable(); // Para US-16

            $table->timestamps();

            // Índice único para evitar facturas duplicadas
            $table->unique(['series', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
