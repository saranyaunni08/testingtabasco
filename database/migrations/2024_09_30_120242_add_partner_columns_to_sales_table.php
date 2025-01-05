<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPartnerColumnsToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->text('partner_percentages')->nullable();    // To store percentages (JSON)
            $table->text('partner_amounts')->nullable();         // To store amounts (JSON)
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('partner_percentages');
            $table->dropColumn('partner_amounts');
        });
    }
}
