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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->nullable();
            $table->enum('type', ['emitido', 'recibido']);
            $table->decimal('amount', 12, 2);
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->date('date_issued');
            $table->date('date_due');
            $table->enum('status', ['pendiente', 'cobrado', 'protestado', 'anulado'])->default('pendiente');
            $table->string('recipient_or_sender');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
