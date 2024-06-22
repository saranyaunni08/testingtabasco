<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlatColumnsToRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('flat_build_up_area', 8, 2)->nullable();
            $table->decimal('flat_super_build_up_price', 8, 2)->nullable();
            $table->decimal('flat_carpet_area', 8, 2)->nullable();
            $table->decimal('flat_carpet_area_price', 8, 2)->nullable();
            $table->decimal('flat_expected_carpet_area_price', 12, 2)->nullable();
            $table->decimal('flat_expected_super_buildup_area_price', 12, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('flat_build_up_area');
            $table->dropColumn('flat_super_build_up_price');
            $table->dropColumn('flat_carpet_area');
            $table->dropColumn('flat_carpet_area_price');
            $table->dropColumn('flat_expected_carpet_area_price');
            $table->dropColumn('flat_expected_super_buildup_area_price');
        });
    }
}
