<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuperBuildupAreaAndCarpetAreaToBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->decimal('super_built_up_area_sq_m', 10, 2)->nullable(); // Add Super Buildup Area (sq m)
            $table->decimal('carpet_area_sq_m', 10, 2)->nullable(); // Add Carpet Area (sq m)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buildings', function (Blueprint $table) {
            $table->dropColumn('super_built_up_area_sq_m');
            $table->dropColumn('carpet_area_sq_m');
        });
    }
}
