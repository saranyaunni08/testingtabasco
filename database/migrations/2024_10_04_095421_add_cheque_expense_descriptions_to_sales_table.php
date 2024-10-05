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
         Schema::table('sales', function (Blueprint $table) {
             $table->string('other_loan_description')->nullable();
             $table->date('installment_date')->nullable();
             $table->integer('no_of_installments')->nullable();
             $table->decimal('grand_total_amount', 10, 2)->nullable();
         });
     }
     
     public function down()
     {
         Schema::table('sales', function (Blueprint $table) {
             $table->dropColumn(['other_loan_description', 'installment_date', 'no_of_installments', 'grand_total_amount']);
         });
     }
     
};
