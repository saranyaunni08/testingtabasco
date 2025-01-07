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

    public function bankpartner($buildingId, Request $request)
    {
        $building = Building::findOrFail($buildingId);
        $title = 'Partner Account';
        $page = 'Partner Account';

        // Get the partner's name from the request
        $partnerName = $request->partner_name ?? '';

        // Get the unique partners
        $uniquePartners = DB::table('cash_installments')
            ->join('sales', 'cash_installments.sale_id', '=', 'sales.id')
            ->join('cashinstallment_payments', 'cash_installments.id', '=', 'cashinstallment_payments.cash_installment_id')
            ->join('partner_cash_installments', 'cashinstallment_payments.id', '=', 'partner_cash_installments.cashinstallment_payment_id')
            ->join('partners', 'partner_cash_installments.partner_id', '=', 'partners.id')
            ->distinct()
            ->select('partners.id', 'partners.first_name')
            ->get();

        // Fetch cash installments for the selected partner
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
                'partners.first_name',
                'cashinstallment_payments.paid_amount',
                'cashinstallment_payments.payment_date',
                'cash_installments.installment_date',
                'cash_installments.installment_number',
                'cash_installments.sale_id',
                'sales.customer_name'
            )
            ->when($partnerName, function ($query) use ($partnerName) {
                return $query->where('partners.first_name', $partnerName);
            })
            ->get();

        // Pass both partner and installments data to the view
        return view('cashbook.partner_bank', compact('cashInstallments', 'building', 'title', 'page', 'uniquePartners', 'partnerName'));
    }

    public function cashbookPDF($buildingId)
    {

        $building = Building::findOrFail($buildingId);

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

        $pdf = PDF::loadView('pdf.cash_account_report_pdf', compact(
            'building',
            'cashInstallments',

            'uniquePartners', // Pass the list of bank names to the view


        ));

        return $pdf->download('cash_account_report.pdf');


    }

    public function cashpartnerPDF(Request $request, $buildingId)
    {
        // Fetch the building details
        $building = Building::findOrFail($buildingId);

        // Get the partner's name from the request query parameter
        $partnerName = $request->input('partner_name', '');

        // Get the unique partners
        $uniquePartners = DB::table('cash_installments')
            ->join('sales', 'cash_installments.sale_id', '=', 'sales.id')
            ->join('cashinstallment_payments', 'cash_installments.id', '=', 'cashinstallment_payments.cash_installment_id')
            ->join('partner_cash_installments', 'cashinstallment_payments.id', '=', 'partner_cash_installments.cashinstallment_payment_id')
            ->join('partners', 'partner_cash_installments.partner_id', '=', 'partners.id')
            ->distinct()
            ->select('partners.id', 'partners.first_name')
            ->get();

        // Fetch cash installments for the selected partner
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
                'partners.first_name',
                'cashinstallment_payments.paid_amount',
                'cashinstallment_payments.payment_date',
                'cash_installments.installment_date',
                'cash_installments.installment_number',
                'cash_installments.sale_id',
                'sales.customer_name'
            )
            ->when($partnerName, function ($query) use ($partnerName) {
                return $query->where('partners.first_name', $partnerName);
            })
            ->get();

        // Load the PDF view with the required data
        $pdf = PDF::loadView('pdf.partner_bank_report_pdf', compact(
            'building',
            'cashInstallments',
            'partnerName',
            'uniquePartners'
        ));

        // Download the PDF
        return $pdf->download('partner_bank_report.pdf');
    }


}
