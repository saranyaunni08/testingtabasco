<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentPaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_id')->constrained()->onDelete('cascade');  // Foreign key to installments table
            $table->decimal('paid_amount', 10, 2);  // Amount paid in this transaction
            $table->date('payment_date');  // Payment date
            $table->timestamps();  // Timestamps for creation and update
        });
    }

    public function down()
    {
        Schema::dropIfExists('installment_payments');
    }
}
