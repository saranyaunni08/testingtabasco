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
            $table->text('partners_percentage')->nullable(); // JSON or text for storing percentage data
            $table->text('partners_percentage_amount')->nullable(); // JSON or text for storing amount data
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('partners_percentage');
            $table->dropColumn('partners_percentage_amount');
        });
    }
    
};
