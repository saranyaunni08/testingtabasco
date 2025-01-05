<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('installment_payments', function (Blueprint $table) {
        $table->string('cheque_number')->nullable()->after('account_holder_name'); // Add cheque number column
    });
}

public function down()
{
    Schema::table('installment_payments', function (Blueprint $table) {
        $table->dropColumn('cheque_number');
    });
}

};
