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
        Schema::create('payroll_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained();
            $table->decimal('gross_salary', 10, 2)->default(0);
            $table->decimal('family_allowance', 10, 2)->default(0);
            $table->decimal('overtime', 10, 2)->default(0);
            $table->decimal('tardiness_discount', 10, 2)->default(0);
            $table->decimal('afp_discount', 10, 2)->default(0);
            $table->decimal('ir_discount', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->decimal('essalud', 10, 2)->default(0); // Aporte empleador
            $table->decimal('cts', 10, 2)->default(0); // Provisión
            $table->decimal('gratification', 10, 2)->default(0); // Provisión
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_lines');
    }
};
