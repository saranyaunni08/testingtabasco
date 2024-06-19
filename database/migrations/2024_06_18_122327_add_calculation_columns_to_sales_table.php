<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCalculationColumnsToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->decimal('room_rate', 15, 2)->nullable()->after('total_sq_ft_for_parking');
            $table->decimal('total_with_gst', 15, 2)->nullable()->after('room_rate');
            $table->decimal('total_with_discount', 15, 2)->nullable()->after('total_with_gst');
            $table->decimal('remaining_balance', 15, 2)->nullable()->after('total_with_discount');
            $table->decimal('total_amount', 15, 2)->nullable()->after('discount_percent'); // Add this line for total_amount
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('room_rate');
            $table->dropColumn('total_with_gst');
            $table->dropColumn('total_with_discount');
            $table->dropColumn('remaining_balance');
            $table->dropColumn('total_amount'); // Add this line to drop total_amount
        });
    }
}
