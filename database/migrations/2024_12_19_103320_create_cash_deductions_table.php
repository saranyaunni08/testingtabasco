<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashDeductionsTable extends Migration
{
    public function up()
    {
        Schema::create('cash_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->decimal('deducted_amount', 10, 2);
            $table->string('deduction_reason');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cash_deductions');
    }
}
