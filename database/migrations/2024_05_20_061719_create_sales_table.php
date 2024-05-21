<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_address');
            $table->string('customer_street');
            $table->string('customer_city');
            $table->string('customer_phone');
            $table->string('customer_pin');
            $table->string('customer_state');
            $table->string('customer_country');
            $table->decimal('sale_amount', 10, 2);
            $table->string('discount_amount');
            $table->decimal('advance_amount', 10, 2)->nullable();
            $table->string('payment_method');
            $table->string('installment_period')->nullable();
            $table->decimal('installment_amount', 10, 2)->nullable();
            $table->string('payment_using')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch')->nullable();
            $table->string('account_num')->nullable();
            $table->string('ifsc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
