<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpectedCustomRateToRoomsTable extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Add expected_custom_rate column
            $table->decimal('expected_custom_rate', 10, 2)->nullable()->after('custom_rate');
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Remove the expected_custom_rate column
            $table->dropColumn('expected_custom_rate');
        });
    }
}
