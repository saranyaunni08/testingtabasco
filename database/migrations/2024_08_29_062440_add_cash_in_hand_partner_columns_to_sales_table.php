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
            $table->string('cash_in_hand_partner_name')->nullable(); // Column for partner name
            $table->enum('cash_in_hand_status', ['paid', 'partially_paid', 'unpaid'])->default('unpaid'); // Column for status
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('cash_in_hand_partner_name');
            $table->dropColumn('cash_in_hand_status');
        });
    }
    
};
