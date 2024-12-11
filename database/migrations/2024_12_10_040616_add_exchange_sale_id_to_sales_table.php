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
            $table->unsignedBigInteger('exchange_sale_id')->nullable()->after('exchangestatus');
            $table->foreign('exchange_sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['exchange_sale_id']);
            $table->dropColumn('exchange_sale_id');
        });
    }
    
};
