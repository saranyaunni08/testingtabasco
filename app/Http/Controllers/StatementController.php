<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class StatementController extends Controller
{
        
    public function cash(Sale $sale)
    {
        // Retrieve cash installments related to the sale
        $cashInstallments = $sale->cash_installments;
    
        // Get the first and last installment dates
        $firstInstallmentDate = $cashInstallments->first()->installment_date ?? now();
        $lastInstallmentDate = $cashInstallments->last()->installment_date ?? now();
    
        // Fetch the first installment amount where installment_number = 1 and status = 'paid'
        $firstCreditAmount = $cashInstallments
            ->where('installment_number', 1)
            ->where('status', 'paid')
            ->first()
            ->installment_amount ?? 0;
    
        // Calculate the total installment amount for the sale
        $totalInstallmentAmount = $cashInstallments->sum('installment_amount');
    
        // Adjust the initial balance using the first credit amount
        $initialBalance = $totalInstallmentAmount - $firstCreditAmount;
    
        // Calculate total debit and credit for display
        $totalDebit = $cashInstallments->sum('debit');
        $totalCredit = $cashInstallments->where('status', 'paid')->sum('installment_amount');
    
        return view('statements.cash', [
            'sale' => $sale,
            'cashInstallments' => $cashInstallments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalCredit' => $totalCredit,
            'initialBalance' => $initialBalance,
            'firstCreditAmount' => $firstCreditAmount, // Pass the first credit amount to the view
            'page' => 'cash',
            'title' => 'Cash Statement',
            'balance' => $totalDebit - $totalCredit,
        ]);
    }
    
        
    public function downloadCashStatement(Sale $sale)
    {
        $cashInstallments = $sale->cash_installments;
        
        $totalCredit = $cashInstallments->where('status', 'paid')->sum('installment_amount');
        $totalDebit = $cashInstallments->sum('debit');
        $balance = $totalDebit - $totalCredit;

        $firstInstallmentDate = $cashInstallments->first()->installment_date;
        $lastInstallmentDate = $cashInstallments->last()->installment_date;

        $pdf = Pdf::loadView('pdf.cash_pdf', compact(
            'sale', 'cashInstallments', 'totalDebit', 'totalCredit', 'balance', 'firstInstallmentDate', 'lastInstallmentDate'
        ));
        

        return $pdf->download('cash_statement.pdf');
    }

    

    public function cheque(Sale $sale)
    {
        $chequeInstallments = $sale->installments;
        $firstInstallmentDate = $chequeInstallments->first()->installment_date ?? now();
        $lastInstallmentDate = $chequeInstallments->last()->installment_date ?? now();
        
        // Calculate the total debit and credit
        $totalDebit = $chequeInstallments->sum('debit');
        $totalCredit = $chequeInstallments->where('status', 'paid')->sum('installment_amount');
    
        return view('statements.cheque', [
            'sale' => $sale,
            'chequeInstallments' => $chequeInstallments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'page' => 'cheque',
            'title' => 'Cheque Statement',
        ]);
    }
    public function downloadChequeStatement($id)
    {
        // Retrieve the sale and related data
        $sale = Sale::findOrFail($id);
        $installments = $sale->installments; // Assuming a relationship
    
        // Other variables
        $firstInstallmentDate = $installments->first()->installment_date ?? now();
        $lastInstallmentDate = $installments->last()->installment_date ?? now();
        $totalDebit = $installments->sum('debit');
        $totalCredit = $installments->sum('credit');
        $title = "Cheque Statement for Sale ID: $id"; // Set the title as desired
        $page = 'cheque-statement'; 
        // Generate the PDF
        $pdf = PDF::loadView('pdf.cheque_pdf', [
            'sale' => $sale,
            'installments' => $installments,
            'firstInstallmentDate' => $firstInstallmentDate,
            'lastInstallmentDate' => $lastInstallmentDate,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit,
            'title' => $title,
            'page' => $page,
        ]);
    
        // Return the PDF as a download
        return $pdf->download('cheque_statement.pdf');
    }
    
    

    public function all(Sale $sale)
    {
        // Retrieve all cash and cheque installments related to the sale
        $cashInstallments = $sale->cash_installments;
        $chequeInstallments = $sale->installments;
    
        // Merge all transactions (cash and cheque installments)
        $transactions = collect();
    
        // Add cash installments to transactions
        foreach ($cashInstallments as $installment) {
            $transactions->push([
                'date' => $installment->installment_date,
                'description' => "{$installment->installment_number} Installement (Cash)",
                'payment_type' => 'Cash',
                'debit' => 0,
                'credit' => $installment->installment_amount,
            ]);
        }
    
        // Add cheque installments to transactions
        foreach ($chequeInstallments as $installment) {
            $transactions->push([
                'date' => $installment->installment_date,
                'description' => "{$installment->installment_number} Installement (Cheque)",
                'payment_type' => 'Cheque',
                'debit' => 0,
                'credit' => $installment->installment_amount,
            ]);
        }
    
        // Add initial sales amounts
        $transactions->push([
            'date' => $sale->sale_date,
            'description' => 'Sales By Cash',
            'payment_type' => 'Cash',
            'debit' => $sale->cash_amount,
            'credit' => 0,
        ]);
    
        $transactions->push([
            'date' => $sale->sale_date,
            'description' => 'Sales',
            'payment_type' => 'Cheque',
            'debit' => $sale->cheque_amount,
            'credit' => 0,
        ]);
    
        // Sort transactions by date
        $transactions = $transactions->sortBy('date');
    
        // Calculate running balance
        $balance = 0;
        foreach ($transactions as &$transaction) {
            $balance += $transaction['debit'] - $transaction['credit'];
            $transaction['balance'] = $balance;
        }
        $title= "all statement";
    
        return view('statements.all', [
            'sale' => $sale,
            'transactions' => $transactions,
            'page' => 'all',
            'title' => $title,

        ]);
    }
    

    public function summary(Sale $sale)
    {
        // Calculate values based on the Sale model and related data
        $saleAmount = $sale->amount; // Assuming this is the total sale amount
        $cashReceived = $sale->cash_received; // Total cash received
        $chequeReceived = $sale->cheque_received; // Total cheque received
        $gst = $sale->gst; // Assuming this is the GST amount
        $totalReceivable = $saleAmount + $gst; // Total amount including GST
        $totalReceived = $cashReceived + $chequeReceived; // Total received amount
        $balanceReceivable = $totalReceivable - $totalReceived; // Remaining balance
        $title= "";

        return view('statements.summary', [
            'sale' => $sale,
            'saleAmount' => $saleAmount,
            'cashReceived' => $cashReceived,
            'chequeReceived' => $chequeReceived,
            'gst' => $gst,
            'totalReceivable' => $totalReceivable,
            'totalReceived' => $totalReceived,
            'balanceReceivable' => $balanceReceivable,
            'page' => 'summary',
            'title' => $title,

        ]);
    }
        
    public function commercialSalesReport()
    {
        $commercialSales = Sale::with('room')
            ->whereHas('room', function ($query) {
                // Additional filters on room, if needed
            })
            ->whereNull('deleted_at') 
            ->get();
    
        // Group sales by floor and room type
        $groupedSalesByFloor = $commercialSales->groupBy(function ($sale) {
            return $sale->room->room_floor ?? 'N/A'; // Group by floor, or 'N/A' if floor is missing
        })->map(function ($sales) {
            return $sales->groupBy(function ($sale) {
                return $sale->room->room_type ?? 'N/A'; // Group each floor's sales by room type
            });
        });
    
        // Calculate totals
        $totalSalesAmount = $commercialSales->sum('final_amount'); 
        $totalCashReceived = $commercialSales->sum('cash_received'); 
        $totalChequeReceived = $commercialSales->sum('cheque_received');
        $totalGST = $commercialSales->sum('gst');
        $totalSqft = $commercialSales->sum(function ($sale) {
            if (!$sale->room) {
                return 0;
            }
            
            // Choose the area based on the area calculation type
            return $sale->area_calculation_type === 'super_build_up_area'
                ? $sale->build_up_area
                : ($sale->area_calculation_type === 'carpet_area' ? $sale->carpet_area : 0);
        });
    
        // Calculate receivables
        $totalReceivable = $totalSalesAmount + $totalGST; 
        $totalReceived = $totalCashReceived + $totalChequeReceived; 
        $balanceReceivable = $totalReceivable - $totalReceived;
    
        // Return the view with the calculated data
        return view('statements.commercial-sales-report', [
            'sales' => $commercialSales,
            'groupedSalesByFloor' => $groupedSalesByFloor, // Pass the new variable to the view
            'totalSalesAmount' => $totalSalesAmount,
            'totalCashReceived' => $totalCashReceived,
            'totalChequeReceived' => $totalChequeReceived,
            'totalGST' => $totalGST,
            'totalReceivable' => $totalReceivable,
            'totalReceived' => $totalReceived,
            'balanceReceivable' => $balanceReceivable,
            'totalSqft' => $totalSqft,
            'page' => 'summary',
            'title' => 'Commercial Sales Report',
        ]);
    }
    
}
