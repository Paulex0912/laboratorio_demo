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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // plazo_fijo, indeterminado, servicio
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('gross_salary', 10, 2);
            $table->string('labor_regime'); // general, mype, cas
            $table->string('afp_type')->nullable(); // integra, prima, profuturo, habitat, onp
            $table->boolean('family_allowance')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
    }
};
