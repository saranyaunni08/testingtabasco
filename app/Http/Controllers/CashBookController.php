<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class CashBookController extends Controller
{
    public function cashbook($buildingId)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Cashbook';
        $page = 'cash book';

        // Get the unique partners (based on partner_cash_installments table)
        $uniquePartners = DB::table('cash_installments')
            ->join('sales', 'cash_installments.sale_id', '=', 'sales.id')
            ->join('cashinstallment_payments', 'cash_installments.id', '=', 'cashinstallment_payments.cash_installment_id')
            ->join('partner_cash_installments', 'cashinstallment_payments.id', '=', 'partner_cash_installments.cashinstallment_payment_id')
            ->join('partners', 'partner_cash_installments.partner_id', '=', 'partners.id')
            ->distinct()
            ->select('partners.id', 'partners.first_name')
            ->get();  // Fetch unique partners


        // Fetch cash installments along with necessary information
        $cashInstallments = DB::table('partner_cash_installments')
            ->join('partners', 'partner_cash_installments.partner_id', '=', 'partners.id')
            ->join('cashinstallment_payments', 'partner_cash_installments.cashinstallment_payment_id', '=', 'cashinstallment_payments.id')
            ->join('cash_installments', 'cashinstallment_payments.cash_installment_id', '=', 'cash_installments.id')
            ->join('sales', 'cash_installments.sale_id', '=', 'sales.id')
            ->select(
                'partner_cash_installments.cashinstallment_payment_id',
                'partner_cash_installments.partner_id',
                'partner_cash_installments.amount',
                'partner_cash_installments.percentage',
                'partners.first_name',  // Fetch first_name from the partners table
                'cashinstallment_payments.paid_amount',
                'cashinstallment_payments.payment_date',
                'cash_installments.installment_date',
                'cash_installments.installment_number',
                'cash_installments.sale_id',
                'sales.customer_name'  // Fetch customer_name from the sales table
            )
            ->get();  // Fetch the installments data



        // Return view with the data
        return view('cashbook.cash_book', compact(
            'building',
            'title',
            'page',
            'cashInstallments',
            'uniquePartners'
        ));

    }


    public function bankpartner($buildingId)
    {

        $building = Building::findOrFail($buildingId);
        $title = 'Partner Account';
        $page = 'Partner Account';
        // Fetching the partner name from the request
        $partnerName = request()->query('partner_name');

        // Fetching partner details from the database based on the name
        $partner = DB::table('partners')
            ->join('sales_partners', 'partners.id', '=', 'sales_partners.partner_id')
            ->join('sales', 'sales_partners.sale_id', '=', 'sales.id')
            ->select('partners.first_name', 'partners.id') // Adjust to select the columns you need
            ->where('partners.first_name', $partnerName)
            ->first(); // Assuming you want the first matching partner

        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id') // Join with sales table
            ->join('sales_partners', 'sales.id', '=', 'sales_partners.sale_id') // Join sales_partners table
            ->join('partners', 'sales_partners.partner_id', '=', 'partners.id') // Join partners table via sales_partners
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.payment_date',
                'installments.paid_amount',
                'installments.sale_id',
                'sales.customer_name',
                'sales.partner_amounts', // Make sure you select partner_amounts from sales
                'partners.first_name',
                'sales_partners.percentage',
                'sales_partners.partner_id'
            )
            ->where('partners.first_name', $partnerName) // Filter by partner name
            ->get();


        // Passing both partner and installments data to the view
        return view('cashbook.partner_bank', compact('partner', 'installments', 'building', 'title', 'page', 'partnerName'));


    }

    public function cashbookPDF($buildingId)
    {

        $building = Building::findOrFail($buildingId);

        // Fetching unique partner first names
        $partnerNames = DB::table('partners')
            ->join('sales_partners', 'partners.id', '=', 'sales_partners.partner_id')
            ->join('sales', 'sales_partners.sale_id', '=', 'sales.id')
            ->select('partners.first_name')
            ->distinct()
            ->get();

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


        $pdf = PDF::loadView('pdf.cash_account_report_pdf', compact(
            'building',
            'installments',
            'partnerNames', // Pass the list of bank names to the view


        ));

        return $pdf->download('cash_account_report.pdf');


    }

    public function cashpartnerPDF($buildingId)
    {

        $building = Building::findOrFail($buildingId);

        $partnerName = request()->query('partner_name');

        // Fetching partner details from the database based on the name
        $partner = DB::table('partners')
            ->join('sales_partners', 'partners.id', '=', 'sales_partners.partner_id')
            ->join('sales', 'sales_partners.sale_id', '=', 'sales.id')
            ->select('partners.first_name', 'partners.id') // Adjust to select the columns you need
            ->where('partners.first_name', $partnerName)
            ->first(); // Assuming you want the first matching partner

        $installments = DB::table('installments')
            ->join('sales', 'installments.sale_id', '=', 'sales.id') // Join with sales table
            ->join('sales_partners', 'sales.id', '=', 'sales_partners.sale_id') // Join sales_partners table
            ->join('partners', 'sales_partners.partner_id', '=', 'partners.id') // Join partners table via sales_partners
            ->select(
                'installments.installment_date',
                'installments.installment_number',
                'installments.payment_date',
                'installments.paid_amount',
                'installments.sale_id',
                'sales.customer_name',
                'sales.partner_amounts', // Make sure you select partner_amounts from sales
                'partners.first_name',
                'sales_partners.percentage',
                'sales_partners.partner_id'
            )
            ->where('partners.first_name', $partnerName) // Filter by partner name
            ->get();



        $pdf = PDF::loadView('pdf.partner_bank_report_pdf', compact(
            'building',
            'installments',
            'partnerName',
            'partner'
        ));

        return $pdf->download('partner_bank_report.pdf');


    }


}
