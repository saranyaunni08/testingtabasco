<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLandColumnsToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Add new columns for land description and amount
            $table->string('land_description', 1000)->nullable();
            $table->decimal('land_amount', 10, 2)->nullable();  // Adjust precision if needed
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Drop the new land columns if rolling back
            $table->dropColumn(['land_description', 'land_amount']);
        });
    }
}
