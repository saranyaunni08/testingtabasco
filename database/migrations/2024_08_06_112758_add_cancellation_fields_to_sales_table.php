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
            $table->decimal('cancellation_fine_amount', 8, 2)->nullable();
            $table->string('cancellation_payment_method')->nullable();
            $table->string('cancellation_bank_id')->nullable();
            $table->string('cancellation_cheque_id')->nullable();
            $table->string('status')->default('active'); // New status column
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('cancellation_fine_amount');
            $table->dropColumn('cancellation_payment_method');
            $table->dropColumn('cancellation_bank_id');
            $table->dropColumn('cancellation_cheque_id');
            $table->dropColumn('status'); // Remove status column
        });
    }
    
    
};
