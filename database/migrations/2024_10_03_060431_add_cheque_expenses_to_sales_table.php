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
            $table->json('cheque_expense_descriptions')->nullable();
            $table->json('cheque_expense_amounts')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['cheque_expense_descriptions', 'cheque_expense_amounts']);
        });
    }
    
};
