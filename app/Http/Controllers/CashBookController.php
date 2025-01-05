<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use Illuminate\Support\Facades\DB;


class CashBookController extends Controller
{
    public function cashbook($buildingId) {
        $building = Building::findOrFail($buildingId);
        $title = 'Cashbook';
        $page = 'cash book';
    
        $installments = DB::table('installments')
        ->join('sales', 'installments.sale_id', '=', 'sales.id') // Join with sales table
        ->join('sales_partners', 'sales.id', '=', 'sales_partners.sale_id') // Correct sales to sales_partners join
        ->join('partners', 'sales_partners.partner_id', '=', 'partners.id') // Now join with partners table via sales_partners
        ->select(
            'installments.installment_date',
            'installments.installment_number',
            'installments.payment_date',
            'installments.paid_amount',
            'installments.sale_id',
            'sales.customer_name',
            'sales.partner_amounts',
            'partners.first_name',
            'sales_partners.percentage',
            'sales_partners.partner_id'
        )
        ->get();
    
    
        // Return view with the data
        return view('cashbook.cash_book', compact(
            'building',
            'title',
            'page',
            'installments'
        ));
    }
    
    public function basheercurrentaccount($buildingId) {
        $building = Building::findOrFail($buildingId);
        $title = 'Basheer Current Account';
        $page = 'Basheer Current Account';
    
        // Fetch the installments for Basheer and join with installment_payments to get the account_holder_name
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->join('sales_partners', 'sales.id', '=', 'sales_partners.sale_id')
            ->join('partners', 'sales_partners.partner_id', '=', 'partners.id')
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id') // Join with installment_payments table
            ->where('installment_payments.account_holder_name', 'Basheer') // Filter by account_holder_name from installment_payments table
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.payment_date',
                'installments.paid_amount',
                'installments.sale_id',
                'sales.customer_name',
                'partners.first_name',
                'sales_partners.percentage',
                'sales_partners.partner_id',
                'installment_payments.account_holder_name' // Select account_holder_name from installment_payments table
            )
            ->get();
    
        // Return view with the data
        return view('cashbook.BasheerCurrentAccount', compact(
            'building',
            'title',
            'page',
            'installments'
        ));
    }
    

    public function pavoorcurrentaccount($buildingId) {
        $building = Building::findOrFail($buildingId);
        $title = 'Pavoor Current Account';
        $page = 'Pavoor Current Account';
    
        // Fetch the installments for Pavoor and join with installment_payments to get the account_holder_name
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->join('sales_partners', 'sales.id', '=', 'sales_partners.sale_id')
            ->join('partners', 'sales_partners.partner_id', '=', 'partners.id')
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id') // Join with installment_payments table
            ->where('installment_payments.account_holder_name', 'Pavoor') // Filter by account_holder_name from installment_payments table
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.payment_date',
                'installments.paid_amount',
                'installments.sale_id',
                'sales.customer_name',
                'partners.first_name',
                'sales_partners.percentage',
                'sales_partners.partner_id',
                'installment_payments.account_holder_name' // Select account_holder_name from installment_payments table
            )
            ->get();
    
        // Return view with the data
        return view('cashbook.PavoorCurrentAccount', compact(
            'building',
            'title',
            'page',
            'installments'
        ));
    }
    
    
    
}
