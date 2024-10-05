<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChequeExpenseColumnsToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->text('cheque_expense_descriptions')->nullable(); // Add the cheque expense descriptions column
            $table->decimal('cheque_expense_amounts', 10, 2)->nullable(); // Add the cheque expense amounts column
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
            $table->dropColumn('cheque_expense_descriptions'); // Remove the cheque expense descriptions column
            $table->dropColumn('cheque_expense_amounts'); // Remove the cheque expense amounts column
        });
    }
}
