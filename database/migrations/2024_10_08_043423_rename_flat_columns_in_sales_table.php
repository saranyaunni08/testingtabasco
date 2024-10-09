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
        Schema::table('sales', function (Blueprint $table) {
            $table->renameColumn('flat_carpet_area','carpet_area');
            $table->renameColumn('flat_build_up_area','build_up_area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->renameColumn('flat_carpet_area','carpet_area');
            $table->renameColumn('flat_build_up_area','build_up_area');
        });
    }
};
