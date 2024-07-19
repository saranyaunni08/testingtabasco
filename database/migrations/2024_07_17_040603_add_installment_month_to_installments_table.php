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
        Schema::table('installments', function (Blueprint $table) {
            $table->string('installment_month')->after('installment_date');
        });
    }
    
    public function down()
    {
        Schema::table('installments', function (Blueprint $table) {
            $table->dropColumn('installment_month');
        });
    }
    
};
