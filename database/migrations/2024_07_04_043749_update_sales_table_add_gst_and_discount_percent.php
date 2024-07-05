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
            $table->float('gst_percent')->after('installments')->nullable();
            $table->float('discount_percent')->after('gst_percent')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('gst_percent');
            $table->dropColumn('discount_percent');
        });
    }
};
