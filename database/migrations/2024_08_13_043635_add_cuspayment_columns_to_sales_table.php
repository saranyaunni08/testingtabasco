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
        $table->string('cuspayment_method')->nullable();
        $table->string('custransfer_id')->nullable();
        $table->string('cuscheque_id')->nullable();
    });
}

public function down()
{
    Schema::table('sales', function (Blueprint $table) {
        $table->dropColumn(['cuspayment_method', 'custransfer_id', 'cuscheque_id']);
    });
}

};
