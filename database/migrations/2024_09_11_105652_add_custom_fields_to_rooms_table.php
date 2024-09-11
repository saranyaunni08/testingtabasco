<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldsToRoomsTable extends Migration
{
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Add custom fields to the rooms table
            $table->string('custom_name')->nullable();
            $table->string('custom_type')->nullable();
            $table->decimal('custom_area', 8, 2)->nullable();
            $table->decimal('custom_rate', 8, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Remove custom fields from the rooms table
            $table->dropColumn('custom_name');
            $table->dropColumn('custom_type');
            $table->dropColumn('custom_area');
            $table->dropColumn('custom_rate');
        });
    }
}
