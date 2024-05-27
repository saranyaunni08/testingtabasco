<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('building_name');
            $table->integer('no_of_floors');
            $table->string('building_address');
            $table->string('street');
            $table->string('city');
            $table->string('pin_code');
            $table->string('state');
            $table->string('country');
            $table->float('super_built_up_area');
            $table->float('carpet_area');
            $table->text('building_amenities')->nullable();
            $table->text('additional_amenities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buildings');
    }
}
