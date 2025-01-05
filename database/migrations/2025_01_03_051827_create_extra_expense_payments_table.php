<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtraExpensePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_expense_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashinstallment_payment_id');
            $table->string('description');
            $table->decimal('percentage', 5, 2);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        
            // Foreign key constraint
            $table->foreign('cashinstallment_payment_id')->references('id')->on('cashinstallment_payments');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('extra_expense_payments');
    }
}
