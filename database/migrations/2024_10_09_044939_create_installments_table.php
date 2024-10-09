<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');
            $table->string('installment_frequency'); // e.g., '3 months'
            $table->date('installment_date');
            $table->integer('installment_number');
            $table->decimal('installment_amount', 15, 2);
            $table->string('status')->default('unpaid'); // 'unpaid', 'paid', etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('installments');
    }
}
