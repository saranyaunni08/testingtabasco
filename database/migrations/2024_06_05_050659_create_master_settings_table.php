<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('master_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('gst_shop', 5, 2)->nullable();
            $table->decimal('gst_flat', 5, 2)->nullable();
            $table->integer('advance_payment_days')->nullable();
            $table->decimal('parking_fixed_rate', 10, 2)->nullable();
            $table->decimal('parking_rate_per_sq_ft', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_settings');
    }
}
