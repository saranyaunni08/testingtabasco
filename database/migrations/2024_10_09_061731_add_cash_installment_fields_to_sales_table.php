<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCashInstallmentFieldsToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('cash_installment_value', 15, 2)->nullable()->after('remaining_cash_value');
            $table->string('cash_loan_type')->nullable()->after('cash_installment_value');
            $table->string('other_loan_description_cash')->nullable()->after('cash_loan_type');
            $table->string('cash_installment_frequency')->nullable()->after('other_loan_description_cash');
            $table->date('cash_installment_start_date')->nullable()->after('cash_installment_frequency');
            $table->integer('cash_no_of_installments')->nullable()->after('cash_installment_start_date');
            $table->decimal('cash_installment_amount', 15, 2)->nullable()->after('cash_no_of_installments');
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'cash_installment_value',
                'cash_loan_type',
                'other_loan_description_cash',
                'cash_installment_frequency',
                'cash_installment_start_date',
                'cash_no_of_installments',
                'cash_installment_amount',
            ]);
        });
    }
}
