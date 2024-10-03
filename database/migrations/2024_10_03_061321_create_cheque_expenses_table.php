<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChequeExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('cheque_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->string('cheque_expense_description')->nullable();
            $table->decimal('cheque_expense_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cheque_expenses');
    }
}
