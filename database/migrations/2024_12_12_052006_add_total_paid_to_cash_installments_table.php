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
        Schema::table('cash_installments', function (Blueprint $table) {
            // Add the 'total_paid' column
            $table->decimal('total_paid', 10, 2)->default(0)->after('installment_amount');
        });
    }
    
    public function down()
    {
        Schema::table('cash_installments', function (Blueprint $table) {
            // Remove the 'total_paid' column
            $table->dropColumn('total_paid');
        });
    }
    
};
