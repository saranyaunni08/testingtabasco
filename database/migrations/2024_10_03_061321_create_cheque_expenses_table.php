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
            $table->string('cheque_expense_descriptions')->nullable(); // Changed to cheque_expense_descriptions
            $table->decimal('cheque_expense_amounts', 10, 2)->nullable(); // Changed to cheque_expense_amounts
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cheque_expenses');
    }
}
