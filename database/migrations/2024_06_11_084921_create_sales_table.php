<?php

// database/migrations/xxxx_xx_xx_create_sales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_contact');
            $table->decimal('sale_amount', 8, 2);
            $table->string('area_calculation_type'); // New field added for area calculation type
            $table->string('calculation_type');
            $table->decimal('parking_rate_per_sq_ft', 8, 2)->nullable();
            $table->decimal('total_sq_ft_for_parking', 8, 2)->nullable();
            $table->decimal('gst_percent', 5, 2);
            $table->string('advance_payment'); // Field to store the timing of advance payment
            $table->decimal('advance_amount', 8, 2)->nullable(); // Field to store the advance amount
            $table->string('payment_method')->nullable();
            $table->string('transfer_id')->nullable();
            $table->string('cheque_id')->nullable();
            $table->date('last_date')->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
