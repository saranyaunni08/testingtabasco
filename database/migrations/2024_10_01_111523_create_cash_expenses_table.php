<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cash_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id'); // Link to sales table
            $table->string('cash_expense_description')->nullable(); // Optional description
            $table->decimal('cash_expense_percentage', 5, 2)->nullable(); // Optional percentage
            $table->decimal('cash_expense_amount', 10, 2)->nullable(); // Optional amount
            $table->timestamps();

            // Set foreign key constraint
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cash_expenses');
    }
};
