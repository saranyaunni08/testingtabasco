<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashinstallmentPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('cashinstallment_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_installment_id');
            $table->decimal('paid_amount', 10, 2);
            $table->date('payment_date');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('cash_installment_id')->references('id')->on('cash_installments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cashinstallment_payments');
    }
}
