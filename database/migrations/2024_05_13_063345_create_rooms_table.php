<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoomsTable extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number');
            $table->string('room_floor')->nullable();
            $table->string('room_type');
            $table->string('build_up_area')->nullable();
            $table->string('carpet_area')->nullable();
            $table->string('flat_rate')->nullable();
            $table->string('super_build_up_price')->nullable();
            $table->string('carpet_area_price')->nullable();
            $table->string('shop_number')->nullable();
            $table->string('shop_type')->nullable();
            $table->string('shop_area')->nullable();
            $table->string('shop_rate')->nullable();
            $table->string('shop_rental_period')->nullable();
            $table->string('parking_number')->nullable();
            $table->string('parking_type')->nullable();
            $table->string('parking_area')->nullable();
            $table->string('parking_rate')->nullable();
            $table->string('space_name')->nullable();
            $table->string('space_type')->nullable();
            $table->string('space_area')->nullable();
            $table->string('space_rate')->nullable();
            $table->string('kiosk_name')->nullable();
            $table->string('kiosk_type')->nullable();
            $table->string('kiosk_area')->nullable();
            $table->string('kiosk_rate')->nullable();
            $table->string('chair_name')->nullable();
            $table->string('chair_type')->nullable();
            $table->string('chair_material')->nullable();
            $table->string('chair_price')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
