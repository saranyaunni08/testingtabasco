<?php

// database/migrations/xxxx_xx_xx_create_buildings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('building_name');
            $table->integer('no_of_floors');
            $table->string('building_address');
            $table->string('street');
            $table->string('city');
            $table->integer('pin_code');
            $table->string('state');
            $table->string('country');
            $table->integer('super_built_up_area');
            $table->integer('carpet_area');
            // Add a JSON column to store amenities
            $table->json('amenities')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
