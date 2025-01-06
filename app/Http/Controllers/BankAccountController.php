<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BankAccountController extends Controller
{
    public function bankaccount($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);
    
        // Fetch unique, non-empty bank names from installment_payments table
        $bankNames = DB::table('installment_payments')
            ->join('installments', 'installment_payments.installment_id', '=', 'installments.id')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->whereNotNull('installment_payments.bank_name')  // Ensure no null values
            ->distinct()
            ->pluck('bank_name')
            ->filter(); // Remove any null or empty values
    
        // Fetch installment details
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id')
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'sales.customer_name',
                'sales.partner_amounts', // This fetches partner amounts for each sale_id
                'installment_payments.bank_name',
                'installment_payments.payment_date',
                'installment_payments.cheque_number',
                'installment_payments.paid_amount'
            )
            ->get()
            ->map(function ($installment) {
                // Decode partner_amounts if it is a JSON string
                $installment->partner_amounts = json_decode($installment->partner_amounts, true);
                return $installment;
            });
    
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
    

    public function banknames($buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);
        $title = 'Bank Account';
        $page = 'bank account';
    
        // Retrieve the bank_name from the query string
        $bankName = request()->query('bank_name');
    
        // Fetch unique, non-empty bank names from installment_payments table
        $bankNames = DB::table('installment_payments')
            ->join('installments', 'installment_payments.installment_id', '=', 'installments.id')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->whereNotNull('installment_payments.bank_name')  // Ensure no null values
            ->distinct()
            ->pluck('bank_name')
            ->filter(); // Remove any null or empty values
    
        // Fetch installments for a specific bank_name
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id')
            ->where('installment_payments.bank_name', $bankName) // Filter by bank_name
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'sales.customer_name',
                'sales.partner_amounts', // This fetches partner amounts for each sale_id
                'installment_payments.bank_name',
                'installment_payments.payment_date',
                'installment_payments.cheque_number',
                'installment_payments.paid_amount'
            )
            ->get();
    
        // Return view with the filtered data
        return view('bankaccount.banknames_bank', compact(
            'building',
            'installments',
            'bankNames', // Pass the list of bank names to the view
            'title',
            'page',
            'bankName' // Make sure this variable is passed to the view
        ));
    }
    
    public function bankaccountPDF($buildingId){

          // Fetch the building details
          $building = Building::findOrFail($buildingId);

          // Fetch unique, non-empty bank names from installment_payments table
          $bankNames = DB::table('installment_payments')
              ->join('installments', 'installment_payments.installment_id', '=', 'installments.id')
              ->join('sales', 'installments.sale_id', '=', 'sales.id')
              ->whereNotNull('installment_payments.bank_name')  // Ensure no null values
              ->distinct()
              ->pluck('bank_name')
              ->filter(); // Remove any null or empty values
  
          // Fetch installment details
          $installments = DB::table('installments')
              ->join('sales', 'installments.sale_id', '=', 'sales.id')
              ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id')
              ->select(
                  'installments.installment_date',
                  'installments.installment_number',
                  'sales.customer_name',
                  'sales.partner_amounts', // This fetches partner amounts for each sale_id
                  'installment_payments.bank_name',
                  'installment_payments.payment_date',
                  'installment_payments.cheque_number',
                  'installment_payments.paid_amount'
              )
              ->get();

              $pdf = PDF::loadView('pdf.bank_account_report_pdf', compact(
                'building',
            'installments',
            'bankNames',
           
            ));
    
            return $pdf->download('bank_account_report.pdf');
    }

    public function banknamesreportPDF($buildingId){

         // Fetch the building details
         $building = Building::findOrFail($buildingId);
         // Retrieve the bank_name from the query string
        $bankName = request()->query('bank_name');
    
        // Fetch unique, non-empty bank names from installment_payments table
        $bankNames = DB::table('installment_payments')
            ->join('installments', 'installment_payments.installment_id', '=', 'installments.id')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->whereNotNull('installment_payments.bank_name')  // Ensure no null values
            ->distinct()
            ->pluck('bank_name')
            ->filter(); // Remove any null or empty values
    
        // Fetch installments for a specific bank_name
        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id')
            ->join('installment_payments', 'installments.id', '=', 'installment_payments.installment_id')
            ->where('installment_payments.bank_name', $bankName) // Filter by bank_name
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'sales.customer_name',
                'sales.partner_amounts', // This fetches partner amounts for each sale_id
                'installment_payments.bank_name',
                'installment_payments.payment_date',
                'installment_payments.cheque_number',
                'installment_payments.paid_amount'
            )
            ->get();

            $pdf = PDF::loadView('pdf.banknames_report_pdf', compact(
                'building',
            'installments',
            'bankNames', // Pass the list of bank names to the view
            'bankName' ,
           
            ));
    
            return $pdf->download('banknames_report.pdf');
    

    }

}


