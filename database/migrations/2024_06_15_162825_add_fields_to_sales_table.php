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
            // Add new columns
            $table->string('area_calculation_type')->nullable();
            $table->decimal('parking_rate_per_sq_ft', 10, 2)->nullable();
            $table->integer('total_sq_ft_for_parking')->nullable();
            $table->decimal('parking_rate_per_sq', 10, 2)->nullable(); // For parking rate per sq ft if not already added
        });
    }
    
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            // Drop columns if needed
            $table->dropColumn('area_calculation_type');
            $table->dropColumn('parking_rate_per_sq_ft');
            $table->dropColumn('total_sq_ft_for_parking');
            $table->dropColumn('parking_rate_per_sq');
        });
    }
    
};
