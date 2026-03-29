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
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'work_order_id')) {
                $table->dropForeign(['work_order_id']);
                $table->dropColumn('work_order_id');
            }
            if (Schema::hasColumn('invoices', 'date_issued')) {
                $table->renameColumn('date_issued', 'issue_date');
            }
            if (Schema::hasColumn('invoices', 'series') && Schema::hasColumn('invoices', 'number')) {
                $table->dropUnique(['series', 'number']);
                $table->dropColumn(['series', 'number']);
            }
            
            $table->string('invoice_type')->nullable()->after('patient_id');
            $table->string('series_number')->nullable()->after('invoice_type');
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('subtotal');
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::table('work_orders', function (Blueprint $table) {
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropColumn('invoice_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('issue_date', 'date_issued');
            $table->string('series', 4)->nullable();
            $table->string('number', 8)->nullable();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->nullOnDelete();
            
            $table->dropForeign(['issued_by']);
            $table->dropColumn(['invoice_type', 'series_number', 'discount_percentage', 'issued_by']);
        });
    }
};
