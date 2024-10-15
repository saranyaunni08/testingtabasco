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
        Schema::create('cash_installments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->string('installment_frequency');
            $table->date('installment_date');
            $table->integer('installment_number');
            $table->decimal('installment_amount', 10, 2);
            $table->string('status')->default('unpaid');
            $table->timestamps();
    
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_installments');
    }
};
