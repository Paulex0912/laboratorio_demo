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
        Schema::create('expense_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_report_id')->constrained()->onDelete('cascade');
            // Reutilizaremos las categorias existentes de la caja
            $table->foreignId('category_id')->nullable()->constrained('cash_categories')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->string('receipt_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_lines');
    }
};
