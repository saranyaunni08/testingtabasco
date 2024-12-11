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
            // Add the exchangestatus column with EX and NX as possible values
            $table->enum('exchangestatus', ['EX', 'NX'])->default('NX');
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Drop the exchangestatus column if rolling back the migration
            $table->dropColumn('exchangestatus');
        });
    }
    
};
