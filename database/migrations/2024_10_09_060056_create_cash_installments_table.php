<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashInstallmentsTable extends Migration
{
    public function up()
    {
        Schema::create('cash_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->string('cash_loan_type'); // e.g., 'bank', 'directors', etc.
            $table->string('other_loan_description_cash')->nullable();
            $table->string('cash_installment_frequency'); // e.g., 'monthly', '3months'
            $table->date('cash_installment_start_date');
            $table->integer('cash_no_of_installments');
            $table->decimal('cash_installment_amount', 15, 2);
            $table->string('status')->default('unpaid'); // 'unpaid', 'paid', etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_installments');
    }
}
