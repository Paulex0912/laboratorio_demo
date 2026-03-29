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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');
            $table->foreignId('general_category_id')->nullable()->constrained()->onDelete('set null'); // Optional category (e.g., Office Supplies, Services)
            $table->foreignId('purchase_order_id')->nullable()->constrained()->onDelete('set null'); // Optional link to a PO
            
            $table->string('bill_number')->unique(); // N° de factura del proveedor (ej. F001-002345)
            $table->date('issue_date');
            $table->date('due_date');
            
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0); // IGV/IVA
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('balance', 10, 2)->default(0); // Amount left to pay
            
            $table->enum('status', ['pendiente', 'parcial', 'pagada', 'anulada'])->default('pendiente');
            
            $table->string('invoice_file_path')->nullable(); // Physical/PDF scan of the bill
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
