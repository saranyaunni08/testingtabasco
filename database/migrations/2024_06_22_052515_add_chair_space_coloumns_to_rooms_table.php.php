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
        Schema::table('rooms', function (Blueprint $table) {
            $table->decimal('chair_space_in_sq', 8, 2)->nullable();
            $table->decimal('chair_space_rate', 8, 2)->nullable();
            $table->decimal('chair_space_expected_rate', 8, 2)->nullable();
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('chair_space_in_sq');
            $table->dropColumn('chair_space_rate');
            $table->dropColumn('chair_space_expected_rate');
        });    }
};
