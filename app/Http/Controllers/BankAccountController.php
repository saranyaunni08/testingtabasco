<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use Illuminate\Support\Facades\DB;

class BankAccountController extends Controller
{
    public function bankaccount($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);

        // Fetch unique bank names from installment_payments table without filtering by building_id
        $bankNames = DB::table('installment_payments')
            ->join('installments', 'installment_payments.installment_id', '=', 'installments.id')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->distinct()
            ->pluck('bank_name'); // Get unique bank names


        // Fetch installment details
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id')
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.paid_amount',
                'sales.customer_name',
                'installment_payments.bank_name',
                'installment_payments.payment_date'
            )
            ->get();

        $title = 'Bank Account';
        $page = 'bank account';

        // Return view with the data
        return view('bankaccount.bank_account', compact(
            'building',
            'installments',
            'bankNames',
            'title',
            'page'
        ));
    }



    public function axisbank($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);

        // Fetch installment details along with related customer and bank information
        // Filter by bank_name = 'Axis Bank'
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id') // Join sales to fetch customer_name
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id') // Join installment_payments to fetch bank_name
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.paid_amount',
                'sales.customer_name',
                'installment_payments.bank_name',
                'installment_payments.payment_date' // Fetch the payment_date
            )
            ->where('installment_payments.bank_name', '=', 'Axis Bank') // Add condition for Axis Bank
            ->get();

        $title = 'Axis Bank Account';
        $page = 'axis bank account';

        // Return view with the data
        return view('bankaccount.axisbankaccount', compact(
            'building',
            'installments',
            'title',
            'page'
        ));
    }


    public function canarabank($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);

        // Fetch installment details along with related customer and bank information
        // Filter by bank_name = 'Canara Bank'
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id') // Join sales to fetch customer_name
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id') // Join installment_payments to fetch bank_name
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.paid_amount as debit',
                'sales.customer_name',
                'installment_payments.bank_name',
                'installment_payments.payment_date' // Fetch the payment_date
            )
            ->where('installment_payments.bank_name', '=', 'Canara Bank') // Change this condition for Canara Bank
            ->get();

        $title = 'Canara Bank Account';
        $page = 'canara bank account';

        // Return view with the data
        return view('bankaccount.canarabankaccount', compact(
            'building',
            'installments',
            'title',
            'page'
        ));
    }


    public function sbi($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);

        // Fetch installment details along with related customer and bank information
        // Filter by bank_name = 'SBI'
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id') // Join sales to fetch customer_name
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id') // Join installment_payments to fetch bank_name
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.paid_amount as debit',
                'sales.customer_name',
                'installment_payments.bank_name',
                'installment_payments.payment_date' // Fetch the payment_date
            )
            ->where('installment_payments.bank_name', '=', 'SBI') // Change this condition for SBI
            ->get();

        $title = 'SBI Account';
        $page = 'sbi account';

        // Return view with the data
        return view('bankaccount.sbiaccount', compact(
            'building',
            'installments',
            'title',
            'page'
        ));
    }


}


