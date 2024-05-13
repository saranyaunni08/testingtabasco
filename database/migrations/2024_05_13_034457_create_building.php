<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('building', function (Blueprint $table) {
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
            $table->string('building_amenities')->nullable();
             $table->string('additional_amenities')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building');
    }
};
