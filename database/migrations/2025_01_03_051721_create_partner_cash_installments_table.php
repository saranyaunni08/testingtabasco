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
        Schema::create('partner_cash_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashinstallment_payment_id'); // Cash installment payment ID
            $table->foreign('cashinstallment_payment_id') // Define the foreign key relationship
                ->references('id')->on('cashinstallment_payments') // Correct table and column
                ->onDelete('cascade'); // Optional: Adjust as per your requirement for cascading deletes
            $table->foreignId('partner_id')->constrained()->onDelete('cascade'); // Partner foreign key
            $table->decimal('percentage', 5, 2); // Percentage for the partner
            $table->decimal('amount', 10, 2); // Amount corresponding to the percentage
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_cash_installments');
    }
};
