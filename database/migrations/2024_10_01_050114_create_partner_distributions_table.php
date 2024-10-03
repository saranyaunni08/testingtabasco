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
        Schema::create('partner_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('partner_id');
            $table->decimal('percentage', 5, 2); // Assuming percentages are up to 2 decimal places
            $table->decimal('amount', 15, 2); // Assuming amounts can have 2 decimal places
            $table->timestamps();
    
            // Define foreign key constraints
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('partners')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('partner_distributions');
    }
    
};
