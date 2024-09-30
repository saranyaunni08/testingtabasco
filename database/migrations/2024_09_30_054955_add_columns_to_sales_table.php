<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSalesTable extends Migration
{
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_contact');
            $table->decimal('sale_amount', 10, 2);
            $table->string('area_calculation_type');
            $table->decimal('flat_build_up_area', 10, 2)->nullable();
            $table->decimal('flat_carpet_area', 10, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->decimal('final_amount', 10, 2)->nullable();
            $table->decimal('cash_value_percentage', 5, 2)->nullable();
            $table->decimal('cash_value_amount', 10, 2)->nullable();
            $table->text('additional_amounts')->nullable();
            $table->decimal('total_cash_value', 10, 2)->nullable();
            $table->decimal('total_received_amount', 10, 2)->nullable();
            $table->text('partners')->nullable();
            $table->text('partner_distribution')->nullable();
            $table->text('other_expenses')->nullable();
            $table->decimal('remaining_cash_value', 10, 2)->nullable();
            $table->string('loan_type')->nullable();
            $table->string('installment_frequency')->nullable();
            $table->date('installment_start_date')->nullable();
            $table->integer('number_of_installments')->nullable();
            $table->decimal('installment_amount', 10, 2)->nullable();
            $table->decimal('gst_percentage', 5, 2)->nullable();
            $table->decimal('gst_amount', 10, 2)->nullable();
            $table->decimal('total_cheque_value_with_gst', 10, 2)->nullable();
            $table->decimal('received_cheque_value', 10, 2)->nullable();
            $table->decimal('balance_amount', 10, 2)->nullable();
            // Add any additional columns as necessary
        });
    }

    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_email',
                'customer_contact',
                'sale_amount',
                'area_calculation_type',
                'flat_build_up_area',
                'flat_carpet_area',
                'discount_percentage',
                'discount_amount',
                'final_amount',
                'cash_value_percentage',
                'cash_value_amount',
                'additional_amounts',
                'total_cash_value',
                'total_received_amount',
                'partners',
                'partner_distribution',
                'other_expenses',
                'remaining_cash_value',
                'loan_type',
                'installment_frequency',
                'installment_start_date',
                'number_of_installments',
                'installment_amount',
                'gst_percentage',
                'gst_amount',
                'total_cheque_value_with_gst',
                'received_cheque_value',
                'balance_amount',
            ]);
        });
    }
}
